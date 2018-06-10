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
/* Eigene Klassen */
use backend\models\Immobilien;
use backend\models\ImmobilienSearch;
use frontend\models\Dateianhang;
use frontend\models\EDateianhang;

class ImmobilienController extends Controller {

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
        $searchModel = new ImmobilienSearch();
        $dataProvider_verkauf = $searchModel->search(Yii::$app->request->queryParams, 1);
        $dataProvider_vermieten = $searchModel->search(Yii::$app->request->queryParams, 2);


        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider_verkauf' => $dataProvider_verkauf,
                    'dataProvider_vermieten' => $dataProvider_vermieten
        ]);
    }

    public function actionView($id) {
        $this->layout = 'main_immo';
        $model = $this->findModel($id);
        $providerBesichtigungstermin = new \yii\data\ArrayDataProvider([
            'allModels' => $model->besichtigungstermins,
        ]);
        $providerEDateianhang = new \yii\data\ArrayDataProvider([
            'allModels' => $model->eDateianhangs,
        ]);
        $providerKundeimmobillie = new \yii\data\ArrayDataProvider([
            'allModels' => $model->kundeimmobillies,
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'providerBesichtigungstermin' => $providerBesichtigungstermin,
                    'providerEDateianhang' => $providerEDateianhang,
                    'providerKundeimmobillie' => $providerKundeimmobillie,
        ]);
    }

    public function actionCreate($id) {
        $this->layout = "main_immo";
        $model_Dateianhang = new Dateianhang(['scenario' => 'create_Dateianhang']);
        $model = new Immobilien();
        $model_e = new EDateianhang();
        $session = new Session();
        $FkInEDatei = array();
        $files = array();
        $extension = array();
        $bezeichnung = array();
        $connection = \Yii::$app->db;
        $expression = new Expression('NOW()');
        $now = (new \yii\db\Query)->select($expression)->scalar();
        $Edateianhang = EDateianhang::find()->all();
        $boolAnhang = false;
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $art = $data['Dateianhang']['l_dateianhang_art_id'];
        }
        if ($model->loadAll(Yii::$app->request->post())) {
            $model_Dateianhang->attachement = UploadedFile::getInstances($model_Dateianhang, 'attachement');
            if ($model_Dateianhang->upload($model_Dateianhang)) {
                $boolAnhang = true;
                $session->addFlash('success', "Der Anhang mit der Bezeichnung $model_Dateianhang->dateiname wurde erolgreich hochgeladen");
            }
            foreach ($model_Dateianhang->attachement as $uploaded_file) {
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
            $isValid = $model_Dateianhang->validate() && $valid;
            if ($isValid) {
                $model->save();
                /* Prüfen, ob in e_dateianhang bereits ein Eintrag ist */
                $Edateianhang = EDateianhang::find()->all();
                foreach ($Edateianhang as $treffer) {
                    array_push($FkInEDatei, $treffer->immobilien_id);
                }
                /* falls nicht */
                if (!in_array($model->id, $FkInEDatei) && $boolAnhang) {
                    $model_e->immobilien_id = $model->id;
                    $model_e->save();
                    $fk = $model_e->id;
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
                $error_model = $model->getErrors();
                $error_anhang = $model_Dateianhang->getErrors();
                foreach ($error_model as $values) {
                    foreach ($values as $ausgabe) {
                        throw new NotAcceptableHttpException(Yii::t('app', $ausgabe));
                    }
                }
                foreach ($error_anhang as $values) {
                    foreach ($values as $ausgabe) {
                        throw new NotAcceptableHttpException(Yii::t('app', $ausgabe));
                    }
                }
            }
        } else {
            if ($id == 1) {
                return $this->render('_form_vermieten', [
                            'model' => $model,
                            'model_Dateianhang' => $model_Dateianhang
                ]);
            } else if ($id == 2) {
                return $this->render('_form_verkauf', [
                            'model' => $model,
                            'model_Dateianhang' => $model_Dateianhang
                ]);
            }
        }
    }

    public function actionUpdate($id) {
        $this->layout = "main_immo";
        $model_Dateianhang = new \frontend\models\Dateianhang();
        $model = $this->findModel($id);
        $form_id = $model->l_art_id;
        if ($model->loadAll(Yii::$app->request->post())) {
            $valid = $model->validate();
            if ($valid) {
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $error_model = $model->getErrors();
                $error_anhang = $model_Dateianhang->getErrors();
                foreach ($error_model as $values) {
                    foreach ($values as $ausgabe) {
                        throw new NotAcceptableHttpException(Yii::t('app', $ausgabe));
                    }
                }
                foreach ($error_anhang as $values) {
                    foreach ($values as $ausgabe) {
                        throw new NotAcceptableHttpException(Yii::t('app', $ausgabe));
                    }
                }
            }
        } else {
            if ($form_id == 1) {
                return $this->render('_form_vermieten', [
                            'model' => $model,
                            'model_Dateianhang' => $model_Dateianhang
                ]);
            } else if ($form_id == 2) {
                return $this->render('_form_verkauf', [
                            'model' => $model,
                            'model_Dateianhang' => $model_Dateianhang
                ]);
            }
        }
    }

    public function actionDeleted($id) {
        $allFiles = array();
        try {
            $session = new Session();
            if (!empty(EDateianhang::findOne(['immobilien_id' => $id]))) {
                $fk = EDateianhang::findOne(['immobilien_id' => $id])->id;
                $idAnhang = Dateianhang::findOne(['e_dateianhang_id' => $fk])->id;
                $picName = Dateianhang::findOne(['e_dateianhang_id' => $fk])->dateiname;
                $url_frontend = $_SERVER["DOCUMENT_ROOT"] . '/yii2_ErkanImmo/frontend/web/img/';
                $filename_frontend = $url_frontend . $picName;
                $url_backend = Yii::getAlias('@pictures') . "/";
                $filename_backend = $url_backend . $picName;
// eruiere alle Dateinamen, die in Dateianhang vermerkt sind
                $FindAllFiles = Dateianhang::find()->all();
                foreach ($FindAllFiles as $files) {
                    array_push($allFiles, $files->dateiname);
                }
//gebe ein Array zurück, in dem die Werte des Arrays array als Schlüssel, und die Häufigkeit ihres Auftretens als Werte angegeben sind.
                $ElementsInArray = array_count_values($allFiles);
//finde alle doppelten Einträge und packe diese Einträge in ein Array
                foreach ($ElementsInArray as $key => $value) {
                    if ($value > 1) {
                        $FilesSeveral[$z] = $key;
                        $z++;
                    }
                }
                foreach ($files as $file) {
                    if ($file->dateiname != NULL) {
                        $filename[$x] = $file->dateiname;
                        $x++;
                    }
                }
                //entferne alle doppelten Elemente aus dem Array
                $filename_unique = array_unique($filename);
                foreach ($filename_unique as $file) {
                    if (in_array($file, $FilesSeveral)) {
                        $session->addFlash('info', 'Der Anhang ' . $file . ' wurde nicht von Ihrem WebSpace entfernt, da er  mehrere mal verwendet wird!');
                        unset($filename_unique[$xy]);
                        $xy++;
                    }
                }
                unlink($filename_backend);
                unlink($filename_frontend);
                $this->findModelAnhang($idAnhang)->deleteWithRelated();
            }
            $this->findModel($id)->delete();
        } catch (IntegrityException $e) {
            $session->addFlash('error', 'Der Löschvorgang verstösst gegen die referentielle Integrität(RI) und wurde deshalb unterbunden. Löschen Sie zuerst all jene Datensätze, auf die sich dieser bezieht! Falls Sie nicht wissen, was RI bedeutet, fragen Sie einen Datenbankexperten.');
            return $this->redirect(['/immobilien/index']);
        }
        $session->addFlash('info', "Der Datensatz mit der Id:$id wurde erfolgreich gelöscht");
        return $this->redirect(['/immobilien/index']);
    }

    public function actionPdf($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id) {
        $model = $this->findModel($id);
        $providerBesichtigungstermin = new \yii\data\ArrayDataProvider([
            'allModels' => $model->besichtigungstermins,
        ]);
        $providerEDateianhang = new \yii\data\ArrayDataProvider([
            'allModels' => $model->eDateianhangs,
        ]);
        $providerKundeimmobillie = new \yii\data\ArrayDataProvider([
            'allModels' => $model->kundeimmobillies,
        ]);

        $content = $this->renderAjax('_pdf', [
            'model' => $model,
            'providerBesichtigungstermin' => $providerBesichtigungstermin,
            'providerEDateianhang' => $providerEDateianhang,
            'providerKundeimmobillie' => $providerKundeimmobillie,
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

    public function actionTermin() {
        ?>
        <h3>
            Diese Methode soll dem Interessenten die Möglichkeit geben, einen Termin mit dem jeweiligen Makler zu beantragen. Es wird folglich ein Formular gerendert, welches die entsprechenden Optionen anbietet. Noch ist das allerdings eine Baustelle
        </h3><br>
        <?php
        print_r("Script in der Klasse " . get_class() . " angehalten");
        die();
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
