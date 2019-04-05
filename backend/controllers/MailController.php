<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use common\models\User;
use kartik\growl\Growl;
use yii\web\Session;
use yii\web\UploadedFile;
use yii\db\Expression;
use backend\models\Mail;
use app\models\MailSearch;
use frontend\models\Dateianhang;
use frontend\models\EDateianhang;
use common\classes\error_handling;

class MailController extends Controller {

    const RenderBackInCaseOfError = '/mail/index';

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
        $searchModel = new MailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $model = $this->findModel($id);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionCreate() {
        $files = array();
        $extension = array();
        $bezeichnung = array();
        $FkInEDatei = array();
        $connection = \Yii::$app->db;
        $expression = new Expression('NOW()');
        $now = (new \yii\db\Query)->select($expression)->scalar();
        $BoolAnhang = false;
        $session = new Session();
        $model = new Mail();
        $modelDateianhang = new Dateianhang(['scenario' => 'create_Dateianhang']);
        $modelEDateianhang = EDateianhang::find()->all();
        $modelE = new EDateianhang();
        $mailFrom = User::findOne(Yii::$app->user->identity->id)->email;
        if ($model->loadAll(Yii::$app->request->post()) && $modelDateianhang->loadAll(Yii::$app->request->post())) {
            /* Anfang der Mailadressenvalidierung */

//Bilde ein Array aus dem Formularfeld der Mailadressen(an)
            $string2Extract = $model->mail_to;
            $extractOuter = explode("'", $string2Extract);
            $extractInner = explode(";", $extractOuter[0]);
//Bilde ein Array aus dem Formularfeld der Mailadressen(cc)
            $string2Extract = $model->mail_cc;
            $extractOuter = explode("'", $string2Extract);
            $extractInnerCc = explode(";", $extractOuter[0]);
//Bilde ein Array aus dem Formularfeld der Mailadressen(bcc)
            $string2Extract = $model->mail_bcc;
            $extractOuter = explode("'", $string2Extract);
            $extractInnerBcc = explode(";", $extractOuter[0]);
            $Ursprung = "Zieladresse";

//Validiere alle Mailadressen
            /* Validiert die Adressen.Eine Kapselung der Logik funktioniert leider nicht,da gekapselte Methoden nicht mehr zurück rendern können */
            for ($i = 0; $i < count($extractInner); $i++) {
                if (!filter_var($extractInner[$i], FILTER_VALIDATE_EMAIL)) {
                    $message = 'Eine oder mehrere der Mailadressen im Feld ' . $Ursprung . ' ist korrupt. Bitte überprüfen Sie deren Validität und reselektieren Sie ggf. Ihre Dateianhänge';
                    $this->Ausgabe($message);
                    /* Dieses Codestück verhindert eine Kapselung der Logik,die demzufolge das erste mal codiert werden muss */
                    return $this->render('create', [
                                'model' => $model,
                                'modelDateianhang' => $modelDateianhang,
                                'mailFrom' => $mailFrom
                    ]);
                }
            }
            if (!empty($model->mail_cc)) {
                $Ursprung = "cc";
                for ($i = 0; $i < count($extractInnerCc); $i++) {
                    if (!filter_var($extractInnerCc[$i], FILTER_VALIDATE_EMAIL)) {
                        $message = 'Eine oder mehrere der Mailadressen im Feld ' . $Ursprung . ' ist korrupt. Bitte überprüfen Sie deren Validität und reselektieren Sie ggf. Ihre Dateianhänge';
                        $this->Ausgabe($message);
                        /* Dieses Codestück verhindert eine Kapselung der Logik,die demzufolge bisher zweimal codiert werden musste */
                        return $this->render('create', [
                                    'model' => $model,
                                    'modelDateianhang' => $modelDateianhang,
                                    'mailFrom' => $mailFrom
                        ]);
                    }
                }
            }
            if (!empty($model->mail_bcc)) {
                $Ursprung = "bcc";
                for ($i = 0; $i < count($extractInnerBcc); $i++) {
                    if (!filter_var($extractInnerBcc[$i], FILTER_VALIDATE_EMAIL)) {
                        $message = 'Eine oder mehrere der Mailadressen im Feld ' . $Ursprung . ' ist korrupt. Bitte überprüfen Sie deren Validität und reselektieren Sie ggf. Ihre Dateianhänge';
                        $this->Ausgabe($message);
                        /* Dieses Codestück verhindert eine Kapselung der Logik,die demzufolge bisher dreimal codiert werden musste */
                        return $this->render('create', [
                                    'model' => $model,
                                    'modelDateianhang' => $modelDateianhang,
                                    'mailFrom' => $mailFrom
                        ]);
                    }
                }
            }
            /* Ende der Mailadressenvalidierung
              Anfang der Modellvalidierung
             */

            $Valid = $this->ValidateModels($model, $modelDateianhang);
            if (is_array($Valid)) {
                $ausgabe1 = print_r('ERROR in der Klasse ' . get_class() . '<br>');
                $ausgabe2 = var_dump($Valid);
                $ausgabe3 = 'Die Tabellen mail oder dateianhang  konnten nicht validiert werden. Informieren Sie den Softwarehersteller!(Fehlercode:ValMailsTZ455)';
                $ausgabeGesamt = $ausgabe1 . '<br>' . $ausgabe2 . '<br>' . $ausgabe3;
                var_dump($ausgabeGesamt);
                print_r('<br>');
                echo Html::a('back', ['/mail/index'], ['title' => 'zurück']);
                die();
            }
            /* Ende der Modellvalidierung */
//Verarbeite Uploaddaten
            $modelDateianhang->attachement = UploadedFile::getInstances($modelDateianhang, 'attachement');
            if ($modelDateianhang->upload($modelDateianhang))
                $BoolAnhang = true;
            else
                throw new NotFoundHttpException(Yii::t('app', "Während des Uploads ging etwas schief. Überprüfen Sie zunächst, ob sie über Schreibrechte verfügen und informieren Sie den Softwarehersteller(Fehlercode:UPx12y34)"));
            if ($BoolAnhang && empty($modelDateianhang->l_dateianhang_art_id)) {
                $message = 'Wenn Sie einen Anhang hochladen, müssen Sie die DropDown-Box Dateianhangsart mit einem Wert belegen.';
                $this->Ausgabe($message, 'Warnung', 1500, Growl::TYPE_WARNING);
                return $this->render('create', [
                            'model' => $model,
                            'modelDateianhang' => $modelDateianhang,
                            'mailFrom' => $mailFrom
                ]);
            }
            /* Ende der Uploadcodierung. Sofern ein Upload hochgeladen wurde, ist er in die entsprechenden Verzeichnisse integriert worden.
              Jetzt muss noch die Datenbanklogik und das Versenden der Mail codiert werden. Dazu werden die models mail,dateianhang und
              e_dateianhang benötigt. Alle wurden bereits instanziert.
             */
//Mailversand:Anfang
//ToDo:Mail versenden

            
//Mailversand:Ende
// Datenbanklogik Anfang: Dazu wird eine Transaction eröffnet. Erst nach dem Commit werden die Records in die Datenbank geschrieben 
            try {
                $transaction = \Yii::$app->db->beginTransaction();
// ersetze deutsche Umlaute im Dateinamen
                foreach ($modelDateianhang->attachement as $uploadedFile) {
                    $umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
                    $ersetzen = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
                    $uploadedFile->name = str_replace($umlaute, $ersetzen, $uploadedFile->name);
// lege jeweils den Dateinamen und dessen Endung in zwei unterschiedliche Arrays ab
                    array_push($files, $uploadedFile->name);
                    array_push($extension, $uploadedFile->extension);
                }
// Differenziere je nach Endung der Elemente im Array die in der Datenbank unten zu speichernden Werte
                for ($i = 0; $i < count($extension); $i++) {
                    if ($extension[$i] == "bmp" || $extension[$i] == "tif" || $extension[$i] == "png" || $extension[$i] == "psd" || $extension[$i] == "pcx" || $extension[$i] == "gif" || $extension[$i] == "cdr" || $extension[$i] == "jpeg" || $extension[$i] == "jpg") {
                        $bez = "Bild für eine Mail";
                        array_push($bezeichnung, $bez);
                    } else {
                        $bez = "Dokumente o.ä. für eine Mail";
                        array_push($bezeichnung, $bez);
                    }
                }
//ab jetzt ist die Mail in die Datenbank gespeichert(na ja, eigentlich erst nach dem Commit). Was folgt ist noch e_dateianhang und dateianhang
                $model->save();
                /* Prüfen, ob in EDateianhang bereits ein Eintrag ist */
                foreach ($modelEDateianhang as $item) {
                    array_push($FkInEDatei, $item->mail_id);
                }
                /* falls nicht */
                if (!in_array($model->id, $FkInEDatei) && $BoolAnhang) {
                    $modelE->mail_id = $model->id;
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
                                [$fk, $modelDateianhang->l_dateianhang_art_id, $bezeichnung[$i], $files[$i], $now, $model->angelegt_von],
                            ])
                            ->execute();
                }
                $transaction->commit();
//Datenbanklogik:Ende
            } catch (\Exception $e) {
                $transaction->rollBack();
                error_handling::error_without_id($e, MailController::RenderBackInCaseOfError);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'modelDateianhang' => $modelDateianhang,
                        'mailFrom' => $mailFrom
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['view', 'id' => $id]);
        } else {
            $mailFrom = User::findOne(Yii::$app->user->identity->id)->email;
            return $this->render('create', [
                        'model' => $model,
                        'mailFrom' => $mailFrom
            ]);
        }
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionPdf($id) {
        $model = $this->findModel($id);

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

    protected function findModel($id) {
        if (($model = Mail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', "Das Model Mail mit der Id:$id konnte nicht geladen werden. Informieren Sie den Softwarehersteller"));
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

    private function ValidateModels($model, $modelDateianhang) {
        $Valid = $model->validate() && $modelDateianhang->validate();
        $ausgabe = array();
        $x = count($ausgabe);
        if (!$Valid) {
            $ausgabe[$x] = "betrifft Model Mail:";
            foreach ($model->getErrors() as $values) {
                foreach ($values as $item) {
                    $x++;
                    $ausgabe[$x] = $item;
                }
            }
            $x = count($ausgabe);
            $ausgabe[$x] = 'betrifft Model Dateianhang:';
            $x = count($ausgabe);
            foreach ($modelDateianhang->getErrors()as $values) {
                foreach ($values as $value3) {
                    $ausgabe[$x] = $value3;
                    $x++;
                }
            }
            return $ausgabe;
        } else {
            return true;
        }
    }

}
