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
use common\classes\error_handling;

class SiteController extends Controller {

    const RenderBackInCaseOfError = '/site/index';

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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

    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            $MenuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
?><?=

            Growl::widget([
                'type' => Growl::TYPE_GROWL,
                'title' => 'Warning',
                'icon' => 'glyphicon glyphicon-ok-sign',
                'body' => 'Da Sie offensichtlich einen User gelöscht hatten, ist momentan kein User mehr angemeldet. Betätigen Sie bitte die Loginoption, rechts oben im Menu!',
                'showSeparator' => true,
                'delay' => 1500,
                'pluginOptions' => [
                    'showProgressbar' => true,
                    'placement' => [
                        'from' => 'top',
                        'align' => 'center',
                    ]
                ]
            ]);
        }
        return $this->render('index');
    }

    public function actionLogin() {
        $this->layout = "main_login";
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $this->redirect(['site/index']);
        } else {
            $model->password = '';
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
        $session = new Session();
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    $session->addFlash('info', "Ein neuer Benutzer der Bezeichnung $model->username wurde soeben neu angelegt!");
                    $fk = $user->id;
                    $modelKopf->data = $data;
                    $modelKopf->user_id = $fk;
                    $modelKopf->save();
                    return $this->goHome();
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
        $this->layout = "reset_main";
        $session = new Session();
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $session->addFlash('success', "Überprüfen Sie ihren Maileingang mit einem Mailclient. Die neuen Zugangsdaten sind dort vermerkt");
                return $this->redirect('site/login');
            } else {
                $session->addFlash('error', "Sorry, we are unable to reset password for the provided email address.");
            }
        }
        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /* Regelt die Logik der Passwortrücksetzung- T2 */

    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
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
            $session = new Session();
            $connection = \Yii::$app->db;
            $record = $connection->createCommand('SELECT COUNT(id) FROM user');
            $UserCount = $record->queryScalar();
            if ($UserCount < 2) {
                $session->addFlash("warning", "Sie würden sich ausperren, da nur ein User im System registriert ist. Legen Sie einen neuen Benuzer an, bevor Sie dieses Feature aufrufen!");
                return $this->redirect(['/site/index']);
            }
            $session = new Session();
            $DynamicModel = new DynamicModel(['id_user']);
            $DynamicModel->addRule(['id_user'], 'integer');
            $DynamicModel->addRule(['id_user'], 'required');
            if ($DynamicModel->load(Yii::$app->request->post())) {
                $kopfId = Kopf::findOne(['user_id' => $DynamicModel->id_user])->id;
                $this->findModel_kopf($kopfId)->delete();
                $this->findModel_user($DynamicModel->id_user)->delete();
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
        $session = new Session();
        $FkInEDatei = array();
        $files = array();
        $connection = \Yii::$app->db;
        $expression = new Expression('NOW()');
        $max = LDateianhangArt::find()->max('id');
        $now = (new \yii\db\Query)->select($expression)->scalar();
        if ($model->loadAll(Yii::$app->request->post())) {
            $model->attachement = UploadedFile::getInstances($model, 'attachement');
            if (empty($model->l_dateianhang_art_id)) {
                echo Growl::widget([
                    'type' => Growl::TYPE_GROWL,
                    'title' => 'Warning',
                    'icon' => 'glyphicon glyphicon-ok-sign',
                    'body' => 'Wenn Sie einen Anhang hochladen, müssen Sie die DropDown-Box Dateianhangsart mit einem Wert belegen.',
                    'showSeparator' => true,
                    'delay' => 1500,
                    'pluginOptions' => [
                        'showProgressbar' => true,
                        'placement' => [
                            'from' => 'top',
                            'align' => 'center',
                        ]
                    ]
                ]);
                return $this->render('_form_bilder', [
                            'model' => $model,
                ]);
            } else {
                if ($model->uploadFrontend($model)) {
                    foreach ($model->attachement as $uploaded_file) {
                        $umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
                        $ersetzen = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
                        array_push($files, $uploaded_file->name);
                        $uploaded_file->name = str_replace($umlaute, $ersetzen, $uploaded_file->name);
                        $session->addFlash('info', "Das Bild $uploaded_file->name wurde soeben hochgeladen! Sie können es jetzt im Frontend verwenden");
                    }
                    /* Prüfen, ob in EDateianhang bereits ein Eintrag ist */
                    $EDateianhang = EDateianhang::find()->all();
                    foreach ($EDateianhang as $treffer) {
                        array_push($FkInEDatei, $treffer->user_id);
                    }
                    /* falls nicht */
                    $UserId=Yii::$app->user->identity->id;
                    if (!in_array($UserId, $FkInEDatei)) {
                        $modelE->user_id = $UserId;
                        $modelE->save();
                        $fk = $modelE->id;
                        /* falls doch */
                    } else {
                        $fk = EDateianhang::findOne(['user_id' => $model->id]);
                        var_dump($fk);
                        die();
                    }
                    /* Speichere Records, abhängig von dem Array($files) in die Datenbank.
                      Da mitunter mehrere Records zu speichern sind, funktioniert das $model-save() nicht. Stattdessen wird batchInsert() verwendet */
                    $bezeichnung="Bilder für das Frontend";
                    for ($i = 0; $i < count($files); $i++) {
                        $connection->createCommand()
                                ->batchInsert('dateianhang', ['e_dateianhang_id', 'l_dateianhang_art_id', 'bezeichnung', 'dateiname', 'angelegt_am', 'angelegt_von'], [
                                    [$fk, $max, $bezeichnung, $files[$i], $now, Yii::$app->user->identity->id],
                                ])
                                ->execute();
                    }
                    $this->redirect(['/site/index']);
                } else {
                    var_dump($model);
                    die();
                }
            }
        } else {
            return $this->render('_form_bilder', [
                        'model' => $model,
            ]);
        }
    }

    protected function findModel_user($id) {
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

    protected function findModel_kopf($id) {
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

}
