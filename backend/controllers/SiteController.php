<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\base\DynamicModel;
use yii\web\Session;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\db\Expression;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\db\IntegrityException;
use kartik\widgets\Growl;
use common\models\LoginForm;
use common\models\User;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use backend\models\SignupForm;
use backend\models\Kopf;
use frontend\models\Dateianhang;
use frontend\models\EDateianhang;
use frontend\models\LDateianhangArt;
use frontend\models\DateianhangSearch;
use common\classes\error_handling;

class SiteController extends Controller {

    const RenderBackInCaseOfError = '/site/index';

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'index'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /* Displays homepage. */

    public function actionIndex($message = NULL) {
        if ($message != null) {
            $session = Yii::$app->session;
            $session->addFlash('info', $message);
        }
        if (Yii::$app->user->isGuest) {
            $MenuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            $message = 'Da Sie offensichtlich einen User gelöscht hatten, ist momentan kein User mehr angemeldet. Betätigen Sie bitte die Loginoption, rechts oben im Menu!';
            $this->Ausgabe($message, 'Warnung', 1500, Growl::TYPE_WARNING);
        }
        $sessionPHP = Yii::$app->session;
        if (!$sessionPHP->isActive)
            $sessionPHP->open();
        $pics = $sessionPHP['bilder'];
        $path = Yii::getAlias('@picturesBackend');
        $modelDateianhang = Dateianhang::find()->all();
        $arrayOfAllFilenames = array();
        foreach ($modelDateianhang as $item) {
            array_push($arrayOfAllFilenames, $item->dateiname);
        }
        $arrayOfFilesNamesUnique = array_unique($arrayOfAllFilenames);
        $arrayOfDifference = array_diff_assoc($arrayOfAllFilenames, $arrayOfFilesNamesUnique);
        $arrayOfDifWithPath = array();
        foreach ($arrayOfDifference as $item) {
            $fileWithPath = $path . DIRECTORY_SEPARATOR . $item;
            array_push($arrayOfDifWithPath, $fileWithPath);
        }
        if (is_array($pics) && $pics != null && count($pics) == 1) {
            $file2BeDeleted = $path . DIRECTORY_SEPARATOR . $pics[0];
            if (file_exists($file2BeDeleted) && !in_array($file2BeDeleted, $arrayOfDifWithPath)) {
                FileHelper::unlink($file2BeDeleted);
            }
        } else if (is_array($pics) && $pics != null && count($pics) > 1) {
            for ($i = 0; $i < count($pics); $i++) {
                $file2BeDeleted = $path . DIRECTORY_SEPARATOR . $pics[$i];
                if (file_exists($file2BeDeleted) && !in_array($file2BeDeleted, $arrayOfDifWithPath)) {
                    FileHelper::unlink($file2BeDeleted);
                }
            }
        }
        $sessionPHP->close();
        return $this->render('index');
    }

    public function actionLogin() {
        $this->layout = "main_login";
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $this->redirect(['site/index']);
        } else {
            $model->password = '';
            $message = 'Bitte erwerben Sie die Zugangsdaten über den Entwickler!';
            $this->Ausgabe($message, 'Info', 1500, Growl::TYPE_GROWL);
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /* Legt einen neuen User an */

    public function actionSignup() {
        $this->layout = "reset_main";
        $ModelUser = User::find()->all();
        $kopfId = Kopf::find()->min('id');
        $data = Kopf::findOne(['id' => $kopfId])->data;
        $modelKopf = new Kopf();
        foreach ($ModelUser as $user) {
            $telefon = $user->telefon;
        }
        $session = Yii::$app->session;
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    $session->addFlash('info', "Ein neuer Makler der Bezeichnung $model->username wurde soeben neu angelegt!");
                    $fk = $user->id;
                    $modelKopf->data = $data;
                    $modelKopf->user_id = $fk;
                    $modelKopf->save();
                    //return $this->goHome();
                    $this->layout = 'main';
                    return $this->render('index');
                }
            }
        }
        return $this->render('signup', [
                    'model' => $model,
                    'telefon' => $telefon
        ]);
    }

    /* Regelt die Logik der Passwortrücksetzung- T1 */

    public function actionRequestPasswordReset() {
        $session = Yii::$app->session;
        try {
            $this->layout = "reset_main";
            $model = new PasswordResetRequestForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->sendEmail()) {
                    $session->addFlash('success', "Überprüfen Sie ihren Maileingang mit einem Mailclient. Die neuen Zugangsdaten sind dort vermerkt");
                    return $this->redirect(['site/login']);
                } else {
                    $session->addFlash('error', "Sorry, we are unable to reset password for the provided email address.");
                }
            }
        } catch (Exception $e) {
            $session->addFlash('error', 'Error: ', $e->getMessage() . ' in file ' . $e->getFile() . ' at line ' . $e->getLine());
        }
        return $this->render('requestPasswordResetToken', ['model' => $model]);
    }

    /* Regelt die Logik der Passwortrücksetzung- T2 */

    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->addFlash('success', 'New password saved.');
            return $this->redirect(['/site/index']);
        }
        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    /* Regelt das Ausloggen, indem es zum Frontend zurück rendert */

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->redirect(\Yii::$app->urlManagerFrontend->baseUrl . '/home');
    }

    /* Löscht einen User aus der Datenbank */

    public function actionDeluser() {
        try {
            $session = Yii::$app->session;
            $connection = \Yii::$app->db;
            $record = $connection->createCommand('SELECT COUNT(id) FROM user');
            $UserCount = $record->queryScalar();
            if ($UserCount < 2) {
                $session->addFlash("warning", "Sie würden sich ausperren, da nur ein User im System registriert ist. Legen Sie einen neuen Benuzer an, bevor Sie dieses Feature aufrufen!");
                return $this->redirect(['/site/index']);
            }
            $session = Yii::$app->session;
            $DynamicModel = new DynamicModel(['id_user']);
            $DynamicModel->addRule(['id_user'], 'integer');
            $DynamicModel->addRule(['id_user'], 'required');
            if ($DynamicModel->load(Yii::$app->request->post())) {
                //$kopfId = Kopf::findOne(['user_id' => $DynamicModel->id_user])->id;
                //$this->findModel_kopf($kopfId)->delete();
                try {
                    $this->findModel_user($DynamicModel->id_user)->delete();
                } catch (IntegrityException $er) {
                    $message = "Der Benutzer kann nicht gelöscht werden, da das gegen die referentielle Integrität verstößt. Löschen Sie zunächst die korrespondierenden Rechnungsrumpfdaten und, sofern vorhanden, alle hochgeladenen Themes!";
                    $this->Ausgabe($message, 'Error', 1500, Growl::TYPE_DANGER);
                    return $this->render('index');
                }
                $session->addFlash('info', "Der User mit der Id $DynamicModel->id_user wurde soeben gelöscht. Sie können sich mit dessen Logindaten zukünftig nicht mehr einloggen.");
                return $this->redirect(['/site/index']);
            } else {
                return $this->render('_form_userdelete', [
                            'DynamicModel' => $DynamicModel
                ]);
            }
        } catch (\Exception $error) {
            error_handling::error_without_id($error, SiteController::RenderBackInCaseOfError);
        }
    }

    public function actionShowuser() {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()
        ]);
        return $this->render('_form_user', [
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate() {
        $model = new Dateianhang(['scenario' => 'create_Dateianhang']);
        $modelE = new EDateianhang();
        $session = Yii::$app->session;
        $FkInEDatei = array();
        $files = array();
        $connection = \Yii::$app->db;
        $expression = new Expression('NOW()');
        $max = LDateianhangArt::find()->max('id');
        $now = (new Query)->select($expression)->scalar();
        $UserId = Yii::$app->user->identity->id;
        if ($model->loadAll(Yii::$app->request->post())) {
            $model->attachement = UploadedFile::getInstances($model, 'attachement');
            //DropdownValues selektiert? Wenn nein....
            if (empty($model->l_dateianhang_art_id)) {
                $message = 'Wenn Sie einen Anhang hochladen, müssen Sie die DropDown-Box Dateianhangsart mit einem Wert belegen.';
                $this->Ausgabe($message, 'Info', 2000, Growl::TYPE_GROWL);
                return $this->render('_form_bilder', [
                            'model' => $model,
                ]);
                //Wenn ja ...
            } else {
                //prüfe den Rückgabewert des eigentlichen Uploads. Falls TRUE...
                if ($model->uploadBackendThemes($model)) {
                    foreach ($model->attachement as $uploadedFile) {
                        $this->Ersetzen($uploadedFile->name);
                        array_push($files, $uploadedFile->name);
                        $session->addFlash('info', "Das Bild $uploadedFile->name wurde soeben hochgeladen! Sie müssen es vorher noch initialisieren.");
                    }
                    /* Alle FK's(user_id) in ein Array verfrachten */
                    $EDateianhang = EDateianhang::find()->all();
                    foreach ($EDateianhang as $treffer) {
                        array_push($FkInEDatei, $treffer->user_id);
                    }
                    /* Prüfen, ob in EDateianhang bereits ein Eintrag ist: 
                      Falls nicht... */
                    if (!in_array($UserId, $FkInEDatei)) {
                        $modelE->user_id = $UserId;
                        $modelE->save();
                        $fk = $modelE->id;
                        /* ...falls doch */
                    } else
                        $fk = EDateianhang::findOne(['user_id' => $UserId])->id;
                    /*  Speichere Records, abhängig von dem Array($files) in die Datenbank.
                      Da mitunter mehrere Records zu speichern sind, funktioniert das $model-save() nicht.
                      Stattdessen wird batchInsert() verwendet */
                    for ($i = 0; $i < count($files); $i++) {
                        $connection->createCommand()
                                ->batchInsert('dateianhang', ['e_dateianhang_id', 'l_dateianhang_art_id', 'bezeichnung', 'dateiname', 'angelegt_am', 'angelegt_von'], [
                                    [$fk, $model->l_dateianhang_art_id, $model->lDateianhangArt->bezeichnung, $files[$i], $now, $UserId]
                                ])
                                ->execute();
                    }
                    $this->redirect(['/site/index']);
                } else {  //...falls FALSE
                    print_r('Während des Uploads ging etwas schief. Bitte informieren Sie den Softwarehersteller über folgende Ausgabe:<br>');
                    if (count($model->getErrors()) == 0)
                        print_r('<br><br>Vermutlich haben Sie keine oder falsche Themes hochgeladen? Erwartet werden übliche Bilddateien(-endungen)!<br>');
                    else {
                        print_r('<strong>Inhalt des Uploadmodels:</strong><br>');
                        var_dump($model->getErrors());
                    }
                    echo Html::a('back', ['/site/create'], ['title' => 'zurück']);
                    die();
                }
            }
        } else {
            return $this->render('_form_bilder', [
                        'model' => $model,
            ]);
        }
    }

    public function actionShow() {
        $hasPics = false;
        $pathFrom = Yii::getAlias('@uploading');
        $files = FileHelper::findFiles($pathFrom);
        if (count($files) < 2) {
            $message = 'Laden Sie zuerst eines oder mehrere Themes hoch. Derzeit können Sie dem Frontend noch kein Theme zuweisen!';
            $this->Ausgabe($message, 'Info', 2000, Growl::TYPE_GROWL);
        }
        $arrayOfFileNames = array();
        $arrayOfBez = array();
        $session = Yii::$app->session;
        $DynamicModel = new DynamicModel(['file']);
        $DynamicModel->addRule('file', 'string');
        $DynamicModel->addRule('file', 'required');
        $arrayOfObjectsForAnhang = Dateianhang::findAll(['l_dateianhang_art_id' => [10, 11]]);
        foreach ($arrayOfObjectsForAnhang as $item) {
            array_push($arrayOfFileNames, $item->dateiname);
            array_push($arrayOfBez, $item->bezeichnung);
        }
        for ($i = 0; $i < count($arrayOfBez); $i++) {
            if (!empty($arrayOfBez) && $arrayOfBez[$i] == "Frontendbilder") {
                $hasPics = true;
                break;
            }
        }
        if (!$hasPics) {
            $session = Yii::$app->session;
            $session->addFlash('warning', "Solange keine Themes für das Frontend hochgeladen wurden, können diese nicht initialisert werden. Laden Sie welche hoch!");
            return $this->render('index');
        }
        if ($DynamicModel->load(Yii::$app->request->post())) {
            if (empty($DynamicModel->file)) {
                $message = 'Bitte selektieren Sie in jeder DropDownbox einen Wert ihrer Wahl';
                $this->Ausgabe($message, 'Warnung', 1000, Growl::TYPE_INFO);
                return $this->render('_form_picsforfrontend', [
                            'DynamicModel' => $DynamicModel,
                            'arrayOfFileNames' => $arrayOfFileNames,
                            'arrayOfBez' => $arrayOfBez
                ]);
            }
            $pathTo = Yii::getAlias('@pictures');
            $filename = $DynamicModel->file;
            $theme = 'Theme.jpg';
            //Da es unter Linux nicht möglich ist, bestehende Bilder zu überschreiben, muss es vorher gelöscht werden
            if (file_exists($pathTo . DIRECTORY_SEPARATOR . $filename))
                FileHelper::unlink($pathTo . DIRECTORY_SEPARATOR . $filename);
            copy($pathFrom . DIRECTORY_SEPARATOR . $filename, $pathTo . DIRECTORY_SEPARATOR . $filename);
            rename($pathTo . DIRECTORY_SEPARATOR . $filename, $pathTo . DIRECTORY_SEPARATOR . $theme);
            $session->addFlash('success', "Herzlichen Glückwunsch. Das Theme $filename wird ab jetzt im Frontend verwendet.");
            return $this->redirect(['/site/index']);
        } else {
            return $this->render('_form_picsforfrontend', [
                        'DynamicModel' => $DynamicModel,
                        'arrayOfFileNames' => $arrayOfFileNames,
                        'arrayOfBez' => $arrayOfBez
            ]);
        }
    }

    public function actionInitialize() {
        $hasPics = false;
        $pathFrom = Yii::getAlias('@uploading');
        $files = FileHelper::findFiles($pathFrom);

        if (count($files) < 2) {
            $message = 'Laden Sie zuerst eines oder mehrere Themes hoch. Derzeit können Sie dem Impressum noch kein Theme zuweisen!';
            $this->Ausgabe($message, 'Info', 2000, Growl::TYPE_GROWL);
        }
        $arrayOfFileNames = array();
        $arrayOfBez = array();
        $session = Yii::$app->session;
        $DynamicModel = new DynamicModel(['bez', 'file', 'art']);
        $DynamicModel->addRule('file', 'string');
        $DynamicModel->addRule('file', 'required');
        $max = LDateianhangArt::find()->max('id');
        $arrayOfObjectsForAnhang = Dateianhang::findAll(['l_dateianhang_art_id' => [10, 11]]);
        foreach ($arrayOfObjectsForAnhang as $item) {
            array_push($arrayOfFileNames, $item->dateiname);
            array_push($arrayOfBez, $item->bezeichnung);
        }
        for ($i = 0; $i < count($arrayOfBez); $i++) {
            if (!empty($arrayOfBez) && $arrayOfBez[$i] == "Impressumbilder") {
                $hasPics = true;
                break;
            }
        }
        if (!$hasPics) {
            $session = Yii::$app->session;
            $session->addFlash('warning', "Solange keine Themes für das Impressum hochgeladen wurden, können diese nicht initialisert werden. Laden Sie welche hoch!");
            return $this->render('index');
        }
        if ($DynamicModel->load(Yii::$app->request->post())) {
            if (empty($DynamicModel->file)) {
                $message = 'Bitte selektieren Sie in jeder DropDownbox einen Wert ihrer Wahl';
                $this->Ausgabe($message, 'Warnung', 1000, Growl::TYPE_INFO);
                return $this->render('_form_picsforimpressum', [
                            'DynamicModel' => $DynamicModel,
                            'max' => $max,
                            'arrayOfFileNames' => $arrayOfFileNames,
                            'arrayOfBez' => $arrayOfBez
                ]);
            }
            $pathTo = Yii::getAlias('@pictures');
            $filename = $DynamicModel->file;
            $theme = 'themeImpressum.jpg';
            copy($pathFrom . '/' . $filename, $pathTo . '/' . $filename);
            rename($pathTo . '/' . $filename, $pathTo . '/' . $theme);
            $session->addFlash('success', "Herzlichen Glückwunsch. Das Theme $filename wird ab jetzt im Frontend verwendet.");
            return $this->redirect(['/site/index']);
        } else {
            return $this->render('_form_picsforimpressum', [
                        'DynamicModel' => $DynamicModel,
                        'max' => $max,
                        'arrayOfFileNames' => $arrayOfFileNames,
                        'arrayOfBez' => $arrayOfBez
            ]);
        }
    }

    //Stellt das Löschformular aller Themes dar
    public function actionDeletion() {
        $session = Yii::$app->session;
        $connection = \Yii::$app->db;
        $record = $connection->createCommand("SELECT COUNT(id) FROM dateianhang WHERE bezeichnung='Impressumbilder'||bezeichnung='Frontendbilder'");
        $countRecords = $record->queryScalar();
        if ($countRecords == 0) {
            $session->addFlash("warning", "Derzeit sind keinerlei Themes im System vermerkt. Laden Sie welche hoch, um sie ggf. löschen zu können!");
            return $this->redirect(['/site/index']);
        }
        $searchModel = new DateianhangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('_form_delete', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    //Löscht ein bliebiges, einzelnes Theme
    public function actionDelete($id) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $modelDa = Dateianhang::find()->all();
            $userIdOfEDateiAnhang = array();
            //Eruiere alle FK's (user_id) und verfrachte den PK der gefundenen Records in ein Array
            $fk = EDateianhang::find()->where(['>', 'user_id', 0])->all();
            foreach ($fk as $item) {
                array_push($userIdOfEDateiAnhang, $item->id);
            }
            $fkOfDateiAnhang = array();
            //Iteriere über dateianhang und verfrachte alle FK's(e_dateianhang_id), die mit obigem Array identisch sind in ein Array
            foreach ($modelDa as $item) {
                for ($i = 0; $i < count($userIdOfEDateiAnhang); $i++) {
                    if ($item->e_dateianhang_id == $userIdOfEDateiAnhang[$i])
                        array_push($fkOfDateiAnhang, $item->e_dateianhang_id);
                }
            }
            //prüfe, ob Werte im Array doppelt vorhanden sind. Wenn ja, darf eDateiAnhang nicht gelöscht werden
            if ($this->hasDupes($fkOfDateiAnhang))
                $deleteEDateiAnhang = false;
            else
                $deleteEDateiAnhang = true;
            $session = Yii::$app->session;
            $connection = \Yii::$app->db;
            $record = $connection->createCommand("SELECT COUNT(id) FROM dateianhang WHERE bezeichnung='Impressumbilder'||bezeichnung='Frontendbilder'");
            $countRecords = $record->queryScalar();
            if ($countRecords == 0) {
                $session->addFlash("warning", "Derzeit sind keinerlei Themes im System vermerkt. Laden Sie welche hoch, um sie ggf. löschen zu können!");
                return $this->redirect(['/site/index']);
            }
            $dateiname = Dateianhang::findOne(['id' => $id])->dateiname;
            $path = Yii::getAlias('@picturesBackend');
            $pathFrom = Yii::getAlias('@uploading');
            $fk = Dateianhang::findOne(['id' => $id])->e_dateianhang_id;
            $model = $this->findModel_dateianhang($id)->delete();
            if ($deleteEDateiAnhang)
                $modelEDateianhang = $this->findModel_eDateianhang($fk)->delete();
            if (file_exists($path . DIRECTORY_SEPARATOR . $dateiname) && file_exists($pathFrom . DIRECTORY_SEPARATOR . $dateiname)) {
                FileHelper::unlink($path . DIRECTORY_SEPARATOR . $dateiname);
                FileHelper::unlink($pathFrom . DIRECTORY_SEPARATOR . $dateiname);
                $session->addFlash('info', "Das Theme mit der ID:$id wurde soeben sowohl aus der Datenbank als auch aus dem Imageverzeichnis gelöscht");
            } else {
                $session->addFlash('info', "Der Löschvorgang war unvollständig, da das Theme physikalisch nicht mehr auf Ihrer Platte ist. Es wurden nur Ihre Datenbankeinträge entfernt.");
            }
            $transaction->commit();
            return $this->redirect(['/site/index']);
        } catch (\Exception $error) {
            error_handling::error_without_id($error, SiteController::RenderBackInCaseOfError);
        }
    }

    //Löscht alle Themes
    public function actionDeleteall() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $session = Yii::$app->session;
            $eDateianhang = EDateianhang::find()->all();
            $arrayOfFk = array();
            $arrayOfFilenames = array();
            //Eruiere zunächst alle Fremdschlüsseleinträge in e_dateianhang für user_id und verfachte die Treffer in ein Array
            foreach ($eDateianhang as $item) {
                if ($item->user_id != null)
                    array_push($arrayOfFk, $item->user_id);
            }
            /*  Itetriere über das Array, eruiere den PK, ermittle den Dateinamen und lösche sodann den Record aus e_dateianhang. Aufgrund der RI werden alle
              korresponiderenden Records mitgelöscht */
            for ($i = 0; $i < count($arrayOfFk); $i++) {
                $pk = EDateianhang::findOne(['user_id' => $arrayOfFk[$i]])->id;
                $modelDateianhang = Dateianhang::find()->all();
                foreach ($modelDateianhang as $item) {
                    if ($item->e_dateianhang_id == $pk)
                        array_push($arrayOfFilenames, $item->dateiname);
                }
                $this->findModel_eDateianhang($pk)->deleteWithRelated();
            }
            //lösche alle im Array vorhandenen Bilder anhand der Dateinamen aus backend/web/img(@picturesBackend)
            $path = Yii::getAlias('@picturesBackend');
            $pathFrom = Yii::getAlias('@uploading');
            $hasBeenDeleted = false;
            for ($i = 0; $i < count($arrayOfFilenames); $i++) {
                if (file_exists($path . DIRECTORY_SEPARATOR . $arrayOfFilenames[$i]) && file_exists($pathFrom . DIRECTORY_SEPARATOR . $arrayOfFilenames[$i])) {
                    FileHelper::unlink($path . DIRECTORY_SEPARATOR . $arrayOfFilenames[$i]);
                    FileHelper::unlink($pathFrom . DIRECTORY_SEPARATOR . $arrayOfFilenames[$i]);
                    $hasBeenDeleted = true;
                }
            }
            if ($hasBeenDeleted)
                $session->addFlash('info', "Sämtliche Themes wurden sowohl aus der Datenbank als auch aus dem Imageverzeichnis gelöscht");
            else
                $session->addFlash('info', "Der Löschvorgang wurde abgebrochen, da die Themes physikalisch nicht mehr auf Ihrer Platte sind.");
            $transaction->commit();
            return $this->redirect(['/site/index']);
        } catch (\Exception $error) {
            error_handling::error_without_id($error, SiteController::RenderBackInCaseOfError);
        }
    }

    private function findModel_user($id) {
        try {
            $user = User::findOne($id); //findAll() gibt ein array aus Objekten zurück.findOne() gibt ein einzelnes Objekt zurück
            if ($user != NULL)
                return $user;
            else
                throw new NotFoundHttpException(Yii::t('app', 'Die Tabelle user konnte nicht geladen werden. Informieren Sie den Softwarehersteller'));
        } catch (\Exception $e) {
            throw new NotFoundHttpException(Yii::t('app', "$e"));
        }
    }

    private function findModel_kopf($id) {
        try {
            $kopf = Kopf::findOne($id); //findAll() gibt ein array aus Objekten zurück.findOne() gibt ein einzelnes Objekt zurück
            if ($kopf != NULL)
                return $kopf;
            else
                throw new NotFoundHttpException(Yii::t('app', 'Die Tabelle user konnte nicht geladen werden. Informieren Sie den Softwarehersteller'));
        } catch (\Exception $e) {
            throw new NotFoundHttpException(Yii::t('app', "$e"));
        }
    }

    private function findModel_dateianhang($id) {
        try {
            $dateianhang = Dateianhang::findOne($id); //findAll() gibt ein array aus Objekten zurück.findOne() gibt ein einzelnes Objekt zurück
            if ($dateianhang != NULL)
                return $dateianhang;
            else
                throw new NotFoundHttpException(Yii::t('app', 'Die Tabelle Dateianhang konnte nicht geladen werden. Informieren Sie den Softwarehersteller'));
        } catch (\Exception $e) {
            throw new NotFoundHttpException(Yii::t('app', "$e"));
        }
    }

    private function findModel_eDateianhang($id) {
        try {
            $eDateianhang = EDateianhang::findOne($id); //findAll() gibt ein array aus Objekten zurück.findOne() gibt ein einzelnes Objekt zurück
            if ($eDateianhang != NULL)
                return $eDateianhang;
            else
                throw new NotFoundHttpException(Yii::t('app', 'Die Tabelle eDateianhang konnte nicht geladen werden. Informieren Sie den Softwarehersteller'));
        } catch (\Exception $e) {
            throw new NotFoundHttpException(Yii::t('app', "$e"));
        }
    }

    private function hasDupes($array) {
        try {
            return count($array) !== count(array_unique($array));
        } catch (\Exception $error) {
            error_handling::error_without_id($error, SiteController::RenderBackInCaseOfError);
        }
    }

    protected function Ausgabe($message, $typus = 'Warnung', $delay = 1000, $type = Growl::TYPE_GROWL) {
        echo Growl::widget([
            'type' => $type,
            'title' => $typus,
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => $message,
            'showSeparator' => true,
            'delay' => $delay,
            'pluginOptions' => [
                'showProgressbar' => true,
                'placement' => [
                    'from' => 'top',
                    'align' => 'center',
                ]
            ]
        ]);
    }

    protected function Ersetzen($string) {
        $umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
        $ersetzen = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
        $string = str_replace($umlaute, $ersetzen, $string);
        return $string;
    }

}
