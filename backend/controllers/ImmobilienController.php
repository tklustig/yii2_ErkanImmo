<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\base\DynamicModel;
use yii\web\NotFoundHttpException;
use yii\web\NotAcceptableHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use yii\web\UploadedFile;
use frontend\models\LPlz;
use yii\db\Query;
use yii\helpers\FileHelper;
use yii\db\Expression;
use kartik\widgets\Growl;
use kartik\widgets\Alert;
use kartik\helpers\Html;
/* Eigene Klassen */
use backend\models\Immobilien;
use backend\models\ImmobilienSearch;
use frontend\models\Dateianhang;
use frontend\models\EDateianhang;
use frontend\models\Besichtigungstermin;
use common\classes\error_handling;

class ImmobilienController extends Controller {

    const RenderBackInCaseOfError = '/immobilien/index';

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $SearchModel = new ImmobilienSearch();
        $DataProviderVerkauf = $SearchModel->search(Yii::$app->request->queryParams, 1);
        $DataProviderVermieten = $SearchModel->search(Yii::$app->request->queryParams, 2);
        return $this->render('index', [
                    'searchModel' => $SearchModel,
                    'dataProvider_verkauf' => $DataProviderVerkauf,
                    'dataProvider_vermieten' => $DataProviderVermieten,
        ]);
    }

    public function actionView($id) {
        $this->layout = 'main_immo';
        $model = $this->findModel($id);
        $ProviderBesichtigungstermin = new \yii\data\ArrayDataProvider([
            'allModels' => $model->besichtigungstermins,
        ]);
        $ProviderEDateianhang = new \yii\data\ArrayDataProvider([
            'allModels' => $model->eDateianhangs,
        ]);
        $ProviderKundeImmobillie = new \yii\data\ArrayDataProvider([
            'allModels' => $model->kundeimmobillies,
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'providerBesichtigungstermin' => $ProviderBesichtigungstermin,
                    'providerEDateianhang' => $ProviderEDateianhang,
                    'providerKundeimmobillie' => $ProviderKundeImmobillie,
        ]);
    }

    public function actionCreate($id) {

        $this->layout = "main_immo";
        $ModelDateianhang = new Dateianhang(['scenario' => 'create_Dateianhang']);
        $model = new Immobilien();
        $modelE = new EDateianhang();
        $FkInEDatei = array();
        $files = array();
        $extension = array();
        $bezeichnung = array();
        $connection = \Yii::$app->db;
        $expression = new Expression('NOW()');
        $now = (new \yii\db\Query)->select($expression)->scalar();
        $EDateianhang = EDateianhang::find()->all();
        $BoolAnhang = false;
        $session = Yii::$app->session;
        try {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                $art = $data['Dateianhang']['l_dateianhang_art_id'];
                $ModelDateianhang->l_dateianhang_art_id = $art;
            }
            if ($model->loadAll(Yii::$app->request->post())) {
                $ModelDateianhang->attachement = UploadedFile::getInstances($ModelDateianhang, 'attachement');
                if ($ModelDateianhang->upload($ModelDateianhang))
                    $BoolAnhang = true;
                if ($BoolAnhang && empty($ModelDateianhang->l_dateianhang_art_id)) {
                    $message = 'Wenn Sie einen Anhang hochladen, müssen Sie die DropDown-Box Dateianhangsart mit einem Wert belegen.';
                    $this->Ausgabe($message);
                    if ($id == 1) {
                        return $this->render('_form_vermieten', [
                                    'model' => $model,
                                    'ModelDateianhang' => $ModelDateianhang
                        ]);
                    } else if ($id == 2) {
                        return $this->render('_form_verkauf', [
                                    'model' => $model,
                                    'ModelDateianhang' => $ModelDateianhang
                        ]);
                    }
                }
                foreach ($ModelDateianhang->attachement as $uploaded_file) {
                    $umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
                    $ersetzen = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
                    $uploaded_file->name = str_replace($umlaute, $ersetzen, $uploaded_file->name);
// lege jede jeweils den Dateinamen und dessen Endung in zwei unterschiedliche Arrays ab
                    array_push($files, $uploaded_file->name);
                    array_push($extension, $uploaded_file->extension);
                }
                // Differenziere je nach Endung der Elemente im Array die in der Datenbank unten zu speichernden Werte
                for ($i = 0; $i < count($extension); $i++) {
                    if ($extension[$i] == "bmp" || $extension[$i] == "tif" || $extension[$i] == "png" || $extension[$i] == "psd" || $extension[$i] == "pcx" || $extension[$i] == "gif" || $extension[$i] == "cdr" || $extension[$i] == "jpeg" || $extension[$i] == "jpg") {
                        $bez = "Bild für eine Immobilie";
                        array_push($bezeichnung, $bez);
                    } else {
                        $bez = "Dokumente o.ä. für eine Immobilie";
                        array_push($bezeichnung, $bez);
                    }
                }
                $model->l_art_id = $id;
                $valid = $model->validate();
                $IsValid = $ModelDateianhang->validate() && $valid;
                if ($IsValid) {
                    $model->angelegt_von = Yii::$app->user->identity->id;
                    $model->save();
                    if ($BoolAnhang) {
                        /* Prüfen, ob in EDateianhang bereits ein Eintrag ist */
                        $EDateianhang = EDateianhang::find()->all();
                        foreach ($EDateianhang as $treffer) {
                            array_push($FkInEDatei, $treffer->immobilien_id);
                        }
                        /* falls nicht */
                        if (!in_array($model->id, $FkInEDatei) && $BoolAnhang) {
                            $modelE->immobilien_id = $model->id;
                            $modelE->save();
                            $fk = $modelE->id;
                            /* falls doch */
                        } else {
                            $fk = EDateianhang::findOne(['immobilien_id' => $model->id])->id;
                        }
                        /* Speichere Records, abhängig von dem Array($files) in die Datenbank.
                          Da mitunter mehrere Records zu speichern sind, funktioniert das $model-save() nicht. Stattdessen wird batchInsert() verwendet */
                        for ($i = 0; $i < count($files); $i++) {
                            $connection->createCommand()
                                    ->batchInsert('dateianhang', ['e_dateianhang_id', 'l_dateianhang_art_id', 'bezeichnung', 'dateiname', 'angelegt_am', 'angelegt_von'], [
                                        [$fk, $art, $bezeichnung[$i], $files[$i], $now, $model->user_id],
                                    ])
                                    ->execute();
                        }
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $ErrorModel = $model->getErrors();
                    $ErrorAnhang = $ModelDateianhang->getErrors();
                    foreach ($ErrorModel as $values) {
                        foreach ($values as $ausgabe) {
                            throw new NotAcceptableHttpException(Yii::t('app', $ausgabe));
                        }
                    }
                    foreach ($ErrorAnhang as $values) {
                        foreach ($values as $ausgabe) {
                            throw new NotAcceptableHttpException(Yii::t('app', $ausgabe));
                        }
                    }
                }
            } else {
                if ($id == 1) {
                    return $this->render('_form_vermieten', [
                                'model' => $model,
                                'ModelDateianhang' => $ModelDateianhang
                    ]);
                } else if ($id == 2) {
                    return $this->render('_form_verkauf', [
                                'model' => $model,
                                'ModelDateianhang' => $ModelDateianhang
                    ]);
                }
            }
        } catch (\Exception $error) {
            error_handling::error_without_id($error, ImmobilienController::RenderBackInCaseOfError);
        }
    }

    public function actionUpdate($id) {
        $this->layout = "main_immo";
        $ModelDateianhang = new Dateianhang(['scenario' => 'create_Dateianhang']);
        $model = $this->findModel($id);
        $FormId = $model->l_art_id;
        $modelE = new EDateianhang();
        $FkInEDatei = array();
        $files = array();
        $extension = array();
        $bezeichnung = array();
        $connection = \Yii::$app->db;
        $expression = new Expression('NOW()');
        $now = (new \yii\db\Query)->select($expression)->scalar();
        $EDateianhang = EDateianhang::find()->all();
        $BoolAnhang = false;
        $session = Yii::$app->session;
        try {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                $art = $data['Dateianhang']['l_dateianhang_art_id'];
                $ModelDateianhang->l_dateianhang_art_id = $art;
            }
            if ($model->loadAll(Yii::$app->request->post())) {
                $ModelDateianhang->attachement = UploadedFile::getInstances($ModelDateianhang, 'attachement');
                if ($ModelDateianhang->upload($ModelDateianhang))
                    $BoolAnhang = true;
                if ($BoolAnhang && empty($ModelDateianhang->l_dateianhang_art_id)) {
                    $message = 'Wenn Sie einen Anhang hochladen, müssen Sie die DropDown-Box Dateianhangsart mit einem Wert belegen.';
                    $this->Ausgabe($message);
                    if ($FormId == 1) {
                        return $this->render('_form_vermieten', [
                                    'model' => $model,
                                    'ModelDateianhang' => $ModelDateianhang
                        ]);
                    } else if ($FormId == 2) {
                        return $this->render('_form_verkauf', [
                                    'model' => $model,
                                    'ModelDateianhang' => $ModelDateianhang
                        ]);
                    }
                }
                foreach ($ModelDateianhang->attachement as $uploaded_file) {
                    $umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
                    $ersetzen = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
                    $uploaded_file->name = str_replace($umlaute, $ersetzen, $uploaded_file->name);
// lege jede jeweils den Dateinamen und dessen Endung in zwei unterschiedliche Arrays ab
                    array_push($files, $uploaded_file->name);
                    array_push($extension, $uploaded_file->extension);
                }
                // Differenziere je nach Endung der Elemente im Array die in der Datenbank unten zu speichernden Werte
                for ($i = 0; $i < count($extension); $i++) {
                    if ($extension[$i] == "bmp" || $extension[$i] == "tif" || $extension[$i] == "png" || $extension[$i] == "psd" || $extension[$i] == "pcx" || $extension[$i] == "gif" || $extension[$i] == "cdr" || $extension[$i] == "jpeg" || $extension[$i] == "jpg") {
                        $bez = "Bild für eine Immobilie";
                        array_push($bezeichnung, $bez);
                    } else {
                        $bez = "Dokumente o.ä. für eine Immobilie";
                        array_push($bezeichnung, $bez);
                    }
                }
                $valid = $model->validate();
                if ($valid) {
                    /*  Hier muss noch der Spechervorgang für dateianhang und edateianhang vorgenommen werden. Dazu kommt derselbe
                      Code wie in actionCreate() zum Einsatz */
                    $model->save();
                    if ($BoolAnhang) {
                        /* Prüfen, ob in EDateianhang bereits ein Eintrag ist */
                        $EDateianhang = EDateianhang::find()->all();
                        foreach ($EDateianhang as $treffer) {
                            array_push($FkInEDatei, $treffer->immobilien_id);
                        }
                        /* falls nicht */
                        if (!in_array($model->id, $FkInEDatei) && $BoolAnhang) {
                            $modelE->immobilien_id = $model->id;
                            $modelE->save();
                            $fk = $modelE->id;
                            /* falls doch */
                        } else {
                            $fk = EDateianhang::findOne(['immobilien_id' => $model->id])->id;
                        }
                        /* Speichere Records, abhängig von dem Array($files) in die Datenbank.
                          Da mitunter mehrere Records zu speichern sind, funktioniert das $model-save() nicht. Stattdessen wird batchInsert() verwendet */
                        for ($i = 0; $i < count($files); $i++) {
                            $connection->createCommand()
                                    ->batchInsert('dateianhang', ['e_dateianhang_id', 'l_dateianhang_art_id', 'bezeichnung', 'dateiname', 'angelegt_am', 'angelegt_von'], [
                                        [$fk, $art, $bezeichnung[$i], $files[$i], $now, $model->user_id],
                                    ])
                                    ->execute();
                        }
                    }
                    for ($i = 0; $i < count($files); $i++) {
                        $session->addFlash('info', "Folgende Dokumente wurden für die Immobilie der Id $id hinzugefügt:$files[$i]");
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $ErrorModel = $model->getErrors();
                    $ErrorAnhang = $ModelDateianhang->getErrors();
                    foreach ($ErrorModel as $values) {
                        foreach ($values as $ausgabe) {
                            throw new NotAcceptableHttpException(Yii::t('app', $ausgabe));
                        }
                    }
                    foreach ($ErrorAnhang as $values) {
                        foreach ($values as $ausgabe) {
                            throw new NotAcceptableHttpException(Yii::t('app', $ausgabe));
                        }
                    }
                }
            } else {
                if ($FormId == 1) {
                    return $this->render('_form_vermieten', [
                                'model' => $model,
                                'ModelDateianhang' => $ModelDateianhang
                    ]);
                } else if ($FormId == 2) {
                    return $this->render('_form_verkauf', [
                                'model' => $model,
                                'ModelDateianhang' => $ModelDateianhang
                    ]);
                }
            }
        } catch (\Exception $error) {
            error_handling::error_without_id($error, ImmobilienController::RenderBackInCaseOfError);
        }
    }

    public function actionDeleted($id) {
        $x = 0;
        $AllFiles = array();
        $FilesSeveral = array();
        $session = Yii::$app->session;
        $ArrayOfAnhangName = array();
        $ArrayOfIdAnhang = array();
        try {
            if (!empty(Besichtigungstermin::findOne(['Immobilien_id' => $id]))) {
                $session->addFlash('info', "Für diese Immobilie existiert ein Besichtigungstermin. Eine Löschung der Immobilie Id:$id ist folglich nicht möglich");
                return $this->redirect(['/immobilien/index']);
            }
            if (!empty(EDateianhang::findOne(['immobilien_id' => $id]))) {
                $fk = EDateianhang::findOne(['immobilien_id' => $id])->id;
                $IdAnhang = Dateianhang::findOne(['e_dateianhang_id' => $fk])->id;
                $PicName = Dateianhang::find()->where(['e_dateianhang_id' => $fk])->all();
//packe die Ids und die Dateinamen in ein Array
                foreach ($PicName as $file) {
                    array_push($ArrayOfAnhangName, $file->dateiname);
                    array_push($ArrayOfIdAnhang, $file->id);
                }
//eruiere die Pfade
                $UrlBackend = Yii::getAlias('@picturesBackend');
                $UrlFrontend = Yii::getAlias('@pictures') . DIRECTORY_SEPARATOR;
// eruiere alle Dateinamen, die in Dateianhang vermerkt sind
                $FindAllFiles = Dateianhang::find()->all();
                foreach ($FindAllFiles as $files) {
                    array_push($AllFiles, $files->dateiname);
                }
//gebe ein Array zurück, in dem die Häufigkeit ihres Auftretens und der jeweilige Dateinamen angegeben sind.
                $ElementsInArray = array_count_values($AllFiles);
//finde alle doppelten Einträge und packe diese Einträge in ein Array. key enthält den Dateinamen, value die Häufigkeit
                foreach ($ElementsInArray as $key => $value) {
                    if ($value > 1) {
                        array_push($FilesSeveral, $key);
                    }
                }
//sofern Dateinamen im Array enthalten, lösche nicht
                if (in_array($PicName, $FilesSeveral)) {
                    $session->addFlash('info', 'Der Anhang ' . $file . " wurde nicht aus Ihrem Webverzeichnis entfernt, da er  mehrere mal verwendet wird");
//andernfalls lösche gemäß der Angaben im Array
                } else {
                    for ($i = 0; $i < count($ArrayOfAnhangName); $i++) {
                        if (file_exists($UrlBackend . DIRECTORY_SEPARATOR . $ArrayOfAnhangName[$i])) {
                            FileHelper::unlink($UrlBackend . DIRECTORY_SEPARATOR . $ArrayOfAnhangName[$i]);
                            $session->addFlash('info', 'Das Bild/Dokument ' . $ArrayOfAnhangName[$i] . " wurde physikalisch aus Ihrem BackendWebverzeichnis entfernt.");
                        } else
                            $session->addFlash('info', 'Das Bild/Dokument ' . $ArrayOfAnhangName[$i] . " konnte aus Ihrem BackendWebverzeichnis physikalisch nicht entfernt werden, da er nicht mehr exisitert.");
                        if (file_exists($UrlFrontend . DIRECTORY_SEPARATOR . $ArrayOfAnhangName[$i])) {
                            FileHelper::unlink($UrlFrontend . DIRECTORY_SEPARATOR . $ArrayOfAnhangName[$i]);
                            $session->addFlash('info', 'Das Bild/Dokument ' . $ArrayOfAnhangName[$i] . " wurde physikalisch aus Ihrem FrontendWebverzeichnis entfernt.");
                        } else
                            $session->addFlash('info', 'Das Bild/Dokument ' . $ArrayOfAnhangName[$i] . " konnte aus Ihrem FrontendWebverzeichnis physikalisch nicht entfernt werden, da er nicht mehr exisitert.");
                    }
                }
                for ($i = 0; $i < count($ArrayOfIdAnhang); $i++) {
                    $this->findModelAnhang($ArrayOfIdAnhang[$i])->deleteWithRelated();
                }
            }
            $this->findModel($id)->deleteWithRelated();
            if (!empty($IdAnhang)) {
                $session->addFlash('info', "Die Immobilie mit der Id:$id wurde erfolgreich gelöscht. Sie hatte Uploads");
            } else {
                $session->addFlash('info', "Die Immobilie mit der Id:$id wurde erfolgreich gelöscht. Sie hatte keinen Upload!");
            }
            return $this->redirect(['/immobilien/index']);
        } catch (\Exception $e) {
            $ausgabe = "Unknown error. Bitte kontaktieren Sie den Softwartehersteller unter Angabe folgender Punkte:<br><1>:" . get_class() . "<br><2>" . $e;
            throw new NotAcceptableHttpException(Yii::t('app', $ausgabe));
        }
    }

    public function actionShow($filename) {
        $session = Yii::$app->session;
        $CompletePath = Yii::getAlias('@documentsImmoB' . '/' . $filename);
        if (file_exists($CompletePath))
            return Yii::$app->response->sendFile($CompletePath, $filename);
        else {
            $session->addFlash('info', "Die Datei $filename existiert nicht (mehr) auf dem Webserver. Löschen Sie die Uploads über das letzte Icon!");
        }
        $this->redirect(['/immobilien/index']);
    }

    public function actionPdf($id) {
        $model = $this->findModel($id);
        $ProviderBesichtigungstermin = new \yii\data\ArrayDataProvider([
            'allModels' => $model->besichtigungstermins,
        ]);
        $ProviderEDateianhang = new \yii\data\ArrayDataProvider([
            'allModels' => $model->eDateianhangs,
        ]);
        $ProviderKundeImmobillie = new \yii\data\ArrayDataProvider([
            'allModels' => $model->kundeimmobillies,
        ]);

        $content = $this->renderAjax('_pdf', [
            'model' => $model,
        ]);

        $pdf = new \kartik\mpdf\Pdf([
            'mode' => \kartik\mpdf\Pdf::MODE_CORE,
            'format' => \kartik\mpdf\Pdf::FORMAT_A4,
            'orientation' => \kartik\mpdf\Pdf::ORIENT_PORTRAIT,
            'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'options' => ['title' => \Yii::$app->name],
            'methods' => [
                'SetHeader' => [\Yii::$app->name],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    public function actionDecide() {
        $this->layout = 'main_immo';
        $DynamicModel = new DynamicModel(['art']);
        $DynamicModel->addRule(['art'], 'integer');
        $DynamicModel->addRule(['art'], 'required');

        if ($DynamicModel->load(Yii::$app->request->post())) {
            $this->redirect(['/immobilien/create', 'id' => $DynamicModel->art]);
        } else {
            return $this->render('_form_decision', [
                        'DynamicModel' => $DynamicModel,
            ]);
        }
    }

    public function actionAuswahl($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('id, plz AS text')
                    ->from('l_plz')
                    ->where(['like', 'plz', $q]);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => LPlz::find($id)->plz];
        }
        return $out;
    }

    public function actionTermin($id) {
        print_r("Übergeben wurde die Id:$id<br>");
        print_r('Diese Option ist in dieser Version nicht verfügbar.<br>');
        print_r('Script wurde in der Klasse ' . get_class() . ' regulär gestoppt!<br>');
        echo Html::a('zurück', ['/immobilien/index'], ['title' => 'zurück']);
    }

    public function actionDeletion($id) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $session = Yii::$app->session;
            $arrayOfAnhangId = array();
            $arrayOfAnhangFilename = array();
            if (!empty(EDateianhang::findOne(['immobilien_id' => $id]))) {
                $pk = EDateianhang::findOne(['immobilien_id' => $id])->id;
                $fileNames = Dateianhang::find()->where(['e_dateianhang_id' => $pk])->all();
                foreach ($fileNames as $item) {
                    array_push($arrayOfAnhangId, $item->id);
                    array_push($arrayOfAnhangFilename, $item->dateiname);
                }
            }

            if (count($arrayOfAnhangId) > 0) {
                for ($i = 0; $i < count($arrayOfAnhangId); $i++) {
                    $pkOfEdateiAnhang = Dateianhang::findOne(['id' => $arrayOfAnhangId[$i]])->e_dateianhang_id;
                    $this->findModelAnhang($arrayOfAnhangId[$i])->delete();
                    $haveRecordsDeleted = true;
                    $session->addFlash('info', "Der Immobilienanhang mit der Id:$arrayOfAnhangId[$i] wurde aus der Datenbank entfernt.");
                }
            } else
                $haveRecordsDeleted = false;
            if ($haveRecordsDeleted)
                $this->findModelEAnhang($pkOfEdateiAnhang)->delete();
            $transaction->commit();
            $frontendImg = Yii::getAlias('@pictures');
            $backendImg = Yii::getAlias('@picturesBackend');
            $frontendDocuments = Yii::getAlias('@documentsImmoF');
            $backendDocuments = Yii::getAlias('@documentsImmoB');
            if (count($arrayOfAnhangFilename) > 0) {
                for ($i = 0; $i < count($arrayOfAnhangFilename); $i++) {
                    if (file_exists($frontendImg . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i])) {
                        FileHelper::unlink($frontendImg . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i]);
                        $session->addFlash('info', "Der Immobilienanhang $arrayOfAnhangFilename[$i] im Ordner $frontendImg wurde physikalisch gelöscht");
                    } else
                        $session->addFlash('info', "Der Immobilienanhang $arrayOfAnhangFilename[$i] existiert nicht (mehr) im Ordner $frontendImg. Folglich wurde er physikalisch auch nicht gelöscht!");
                    if (file_exists($backendImg . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i])) {
                        FileHelper::unlink($backendImg . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i]);
                        $session->addFlash('info', "Der Immobilienanhang $arrayOfAnhangFilename[$i] im Ordner $backendImg wurde physikalisch gelöscht");
                    } else
                        $session->addFlash('info', "Der Immobilienanhang $arrayOfAnhangFilename[$i] existiert nicht (mehr) im Ordner $backendImg. Folglich wurde er physikalisch auch nicht gelöscht!");
                    if (file_exists($frontendDocuments . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i])) {
                        FileHelper::unlink($frontendDocuments . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i]);
                        $session->addFlash('info', "Der Immobilienanhang $arrayOfAnhangFilename[$i] im Ordner $frontendDocuments wurde physikalisch gelöscht");
                    } else
                        $session->addFlash('info', "Der Immobilienanhang $arrayOfAnhangFilename[$i] existiert nicht (mehr) im Ordner $frontendDocuments. Folglich wurde er physikalisch auch nicht gelöscht!");
                    if (file_exists($backendDocuments . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i])) {
                        FileHelper::unlink($backendDocuments . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i]);
                        $session->addFlash('info', "Der Immobilienanhang $arrayOfAnhangFilename[$i] im Ordner $backendDocuments wurde physikalisch gelöscht");
                    } else
                        $session->addFlash('info', "Der Immobilienanhang $arrayOfAnhangFilename[$i] existiert nicht (mehr) im Ordner $backendDocuments. Folglich wurde er physikalisch auch nicht gelöscht!");
                }
            }
        } catch (\Exception $error) {
            $transaction->rollBack();
            error_handling::error_without_id($error, ImmobilienController::RenderBackInCaseOfError);
        }
        $this->redirect(['/immobilien/index']);
    }

    protected function findModel($id) {
        if (($model = Immobilien::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Das angeforderte Model immobilien konnte nicht geladen werden:(Fehlercode:QQW117)'));
        }
    }

    protected function findModelAnhang($id) {
        if (($model = Dateianhang::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Das angeforderte Model dateianhang konnte nicht geladen werden(Fehlercode:GII995)'));
        }
    }

    protected function findModelEAnhang($id) {
        if (($model = EDateianhang::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Das angeforderte Model edateianhang konnte nicht geladen werden.(Errorcode:FFT448)'));
        }
    }

    private function Ausgabe($message, $typus = 'Warnung', $delay = 1000, $type = Growl::TYPE_GROWL) {
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

}
