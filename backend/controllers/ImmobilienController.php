<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\base\DynamicModel;
use yii\web\NotFoundHttpException;
use yii\web\NotAcceptableHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use yii\db\IntegrityException;
use yii\web\UploadedFile;
use frontend\models\LPlz;
use yii\db\Query;
use yii\db\Expression;
use kartik\widgets\Growl;
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
        $session = new Session();
        try {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                $art = $data['Dateianhang']['l_dateianhang_art_id'];
                $ModelDateianhang->l_dateianhang_art_id = $art;
            }
            if ($model->loadAll(Yii::$app->request->post())) {
                $ModelDateianhang->attachement = UploadedFile::getInstances($ModelDateianhang, 'attachement');
                if ($ModelDateianhang->upload($ModelDateianhang)) {
                    $BoolAnhang = true;
                }else{
                     throw new NotFoundHttpException(Yii::t('app', "Während des Uploads ging etwas schief. Überprüfen Sie zunächst, ob sie über Schreibrechte verfügen und informieren Sie den Softwarehersteller(Fehlercode:UPx12y34)"));
                }
                if ($BoolAnhang && empty($ModelDateianhang->l_dateianhang_art_id)) {
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
                    if ($id == 1) {
                        return $this->render('_form_vermieten', [
                                    'model' => $model,
                                    'model_Dateianhang' => $ModelDateianhang
                        ]);
                    } else if ($id == 2) {
                        return $this->render('_form_verkauf', [
                                    'model' => $model,
                                    'model_Dateianhang' => $ModelDateianhang
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
                    $model->save();
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
                        $fk = EDateianhang::findOne(['immobilien_id' => $model->id]);
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
                                'model_Dateianhang' => $ModelDateianhang
                    ]);
                } else if ($id == 2) {
                    return $this->render('_form_verkauf', [
                                'model' => $model,
                                'model_Dateianhang' => $ModelDateianhang
                    ]);
                }
            }
        } catch (\Exception $error) {
            error_handling::error_without_id($error, ImmobilienController::RenderBackInCaseOfError);
        }
    }

    public function actionUpdate($id) {
        $this->layout = "main_immo";
        $ModelDateianhang = new Dateianhang();
        $model = $this->findModel($id);
        $FormId = $model->l_art_id;
        try {
            if ($model->loadAll(Yii::$app->request->post())) {
                $valid = $model->validate();
                if ($valid) {
                    $model->save();
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
                                'model_Dateianhang' => $ModelDateianhang
                    ]);
                } else if ($FormId == 2) {
                    return $this->render('_form_verkauf', [
                                'model' => $model,
                                'model_Dateianhang' => $ModelDateianhang
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
        $session = new Session();
        $ArrayOfPicName = array();
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
                    array_push($ArrayOfPicName, $file->dateiname);
                    array_push($ArrayOfIdAnhang, $file->id);
                }
//eruiere die Pfade
                $UrlFrontend = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . 'yii2_ErkanImmo/frontend/web/img/';
                $UrlBackend = Yii::getAlias('@pictures') . DIRECTORY_SEPARATOR;
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
                    for ($i = 0; $i < count($ArrayOfPicName); $i++) {
                        if (file_exists($UrlBackend . $ArrayOfPicName[$i])) {
                            unlink($UrlBackend . $ArrayOfPicName[$i]);
                            unlink($UrlFrontend . $ArrayOfPicName[$i]);
                        }
                        $session->addFlash('info', 'Der Anhang ' . $ArrayOfPicName[$i] . " wurde aus Ihrem Webverzeichnis entfernt.");
                    }
                }
                for ($i = 0; $i < count($ArrayOfIdAnhang); $i++) {
                    $this->findModelAnhang($ArrayOfIdAnhang[$i])->deleteWithRelated();
                }
            }
            $this->findModel($id)->deleteWithRelated();
            if (!empty($IdAnhang)) {
                $session->addFlash('info', "Der Datensatz mit der Id:$id wurde erfolgreich gelöscht");
            } else {
                $session->addFlash('info', "Der Datensatz mit der Id:$id wurde erfolgreich gelöscht. Er hatte keinen Anhang!");
            }
            return $this->redirect(['/immobilien/index']);
        } catch (IntegrityException $e) {
            $ausgabe = "Unknown error. Bitte kontaktieren Sie den Softwartehersteller unter Angabe folgender Punkte:<br><1>:" . get_class() . "<br><2>" . $e;
            throw new NotAcceptableHttpException(Yii::t('app', $ausgabe));
        }
    }

    public function actionShow($filename) {
        $CompletePath = Yii::getAlias('@pictures' . '/' . $filename);
        return Yii::$app->response->sendFile($CompletePath, $filename);
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

    protected function findModel($id) {
        if (($model = Immobilien::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Das angeforderte Model konnte nicht geladen werden'));
        }
    }

    protected function findModelAnhang($id) {
        if (($model = Dateianhang::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Das angeforderte Model konnte nicht geladen werden'));
        }
    }

}
