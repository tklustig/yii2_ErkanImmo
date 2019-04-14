<?php

namespace backend\controllers;

use Yii;
use frontend\models\Kunde;
use backend\models\KundeSearch;
use frontend\models\LPlz;
use backend\models\Bankverbindung;
use frontend\models\Dateianhang;
use frontend\models\EDateianhang;
use common\classes\error_handling;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\db\IntegrityException;
use yii\web\Session;
use yii\db\Expression;
use yii\helpers\FileHelper;
use kartik\growl\Growl;

class KundeController extends Controller {

    const RenderBackInCaseOfError = '/kunde/index';

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
        $countKunde = Kunde::find()->count('id');
        if ($countKunde == 0) {
            $session = new Session();
            $session->addFlash('info', 'Es exisitert noch kein Kunde in der Datenbank. Steigern Sie Ihre Kundenaqkuise!');
            return $this->redirect(['/site/index']);
        }
        $searchModel = new KundeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $model = $this->findModel($id);
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdate($id) {
        $model = new Bankverbindung();
        $model = $this->findModel($id);
        $plzId = $model->l_plz_id;
        $bankId = $model->bankverbindung_id;
        $modelDateianhang = new Dateianhang(['scenario' => 'create_Dateianhang']);
        $modelE = new EDateianhang();
        $FkInEDatei = array();
        $files = array();
        $bezeichnung = array();
        $connection = \Yii::$app->db;
        $expression = new Expression('NOW()');
        $now = (new \yii\db\Query)->select($expression)->scalar();
        $EDateianhang = EDateianhang::find()->all();
        $BoolAnhang = false;
        //Plzstring im Formular ausgeben
        if (!empty(LPlz::findOne(['id' => $plzId])))
            $plz = LPlz::findOne(['id' => $plzId])->plz;
        else
            $plz = "00000";
        if ($model->loadAll(Yii::$app->request->post()) && $modelDateianhang->loadAll(Yii::$app->request->post())) {
            //den Plzstring in die Id zurück verwandeln
            $plzID = LPlz::findOne(['plz' => $model->l_plz_id])->id;
            $model->l_plz_id = $plzID;
            //den Bankstring in die Id zurück verwandeln
            if (!empty(Bankverbindung::findOne(['id' => $model->bankverbindung_id]))) {
                $bankID = Bankverbindung::findOne(['id' => $model->bankverbindung_id])->id;
                $model->bankverbindung_id = $bankID;
            }
            /* ToDo: prüfen, ob bankverbindung_id versehentlich über die Select2Box(_form.php) doppelt gesetzt wurde. Falls ja, darf der Record
              nicht gespeichert werden, da zwei Kunden nicht ein-und diesselbe Bankdaten haben können. Die Benachrichtigung soll über eine
              kartikBox erfolgen, gefolgt von einem render auf actionUpdate */
            $modelDateianhang->attachement = UploadedFile::getInstances($modelDateianhang, 'attachement');
            if ($modelDateianhang->uploadBackend($modelDateianhang))
                $BoolAnhang = true;
            if ($BoolAnhang && empty($modelDateianhang->l_dateianhang_art_id)) {
                echo Growl::widget([
                    'type' => Growl::TYPE_GROWL,
                    'title' => 'Warning',
                    'icon' => 'glyphicon glyphicon-ok-sign',
                    'body' => 'Wenn Sie einen Anhang hochladen, müssen Sie die DropDown-Box Dateianhangsart mit einem Wert belegen. Das soeben hochgeladene Kundenbild wurde wieder entfernt. Reselektieren Sie es ggf.',
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
                foreach ($modelDateianhang->attachement as $uploadedFile) {
                    FileHelper::unlink(Yii::getAlias('@picturesBackend') . DIRECTORY_SEPARATOR . $uploadedFile->name);
                }
                return $this->render('update', [
                            'model' => $model,
                            'plz' => $plz,
                            'id' => $bankId,
                            'modelDateianhang' => $modelDateianhang
                ]);
            }
            foreach ($modelDateianhang->attachement as $uploadedFile) {
                $umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
                $ersetzen = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
                $uploadedFile->name = str_replace($umlaute, $ersetzen, $uploadedFile->name);
// lege jede den Dateinamen  in das Array ab
                array_push($files, $uploadedFile->name);
            }
            $valid = $model->validate();
            $IsValid = $modelDateianhang->validate() && $valid;
            if ($IsValid) {
                $transaction = \Yii::$app->db->beginTransaction();
                $model->save();
                /* Prüfen, ob in EDateianhang bereits ein Eintrag ist */
                $EDateianhang = EDateianhang::find()->all();
                foreach ($EDateianhang as $treffer) {
                    array_push($FkInEDatei, $treffer->kunde_id);
                }
                /* falls nicht */
                if (!in_array($model->id, $FkInEDatei) && $BoolAnhang) {
                    $modelE->kunde_id = $model->id;
                    $modelE->save();
                    $fk = $modelE->id;
                    /* falls doch */
                } else {
                    $fk = EDateianhang::findOne(['kunde_id' => $model->id]);
                }
                /* Speichere Records, abhängig von dem Array($files) in die Datenbank.
                  Da mitunter mehrere Records zu speichern sind, funktioniert das $model-save() nicht. Stattdessen wird batchInsert() verwendet */
                if (!empty($model->aktualisiert_von))
                    $aktualisiertVon = $model->aktualisiert_von;
                else
                    $aktualisiertVon = Yii::$app->user->identity->id;
                for ($i = 0; $i < count($files); $i++) {
                    $connection->createCommand()
                            ->batchInsert('dateianhang', ['e_dateianhang_id', 'l_dateianhang_art_id', 'bezeichnung', 'dateiname', 'angelegt_am', 'angelegt_von'], [
                                [$fk, $modelDateianhang->l_dateianhang_art_id, 'Kundenbild', $files[$i], $now, $aktualisiertVon],
                            ])
                            ->execute();
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $ErrorModel = $model->getErrors();
                $ErrorAnhang = $modelDateianhang->getErrors();
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
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'plz' => $plz,
                        'id' => $bankId,
                        'modelDateianhang' => $modelDateianhang
            ]);
        }
    }

    public function actionDelpic($id) {
        print_r("Übergeben wurde der Pk:$id von EDateianhang. Damit lässt sich in dateianhang der Bildname und der PK auslesen,um somit den Löschvorgang codieren zu können!");
        die();
    }

    public function actionDelete($id) {
        try {
            $session = new Session();
            $this->findModel($id)->delete();
            $session->addFlash('info', "Der Kunde mit der Id:$id wurde aus der Datenbank entfernt!");
            return $this->redirect(['/site/index']);
        } catch (IntegrityException $er) {
            $message = "Der Kunde mit der Id:$id kann nicht gelöscht werden, da das gegen die referentielle Integrität verstößt. Löschen Sie zunächst die korrespondierenden Bankdaten und/oder Besichtigungstermine!";
            $this->message($message, 'Error', 1500, Growl::TYPE_DANGER);
            $searchModel = new KundeSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
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

    public function actionSend() {
        /* Die Methode redirect() akzeptiert weder Arrays noch Objekte als Übergabeparameter. Demzufolge werden diese Parameter einer Session
          übergeben, die dann vom jeweiligen Controller aufgerufen wird */
        $session = new Session();
        $Mailadresses = array();
        $Name = array();
        $Geschlecht = array();
        /* Indizie für Adressen */
        $x = 0;
        /* Indizie für den Fremdschlüssel */
        $y = 0;
        /* Indizie für Geschlecht */
        $z = 0;
        /* Indizie für Namen */
        $zz = 0;
        try {
            $checkbox = (array) Yii::$app->request->post('selection');
            if (empty(($checkbox)) && (isset($_POST['button_checkBoxes']))) {
                $session->addFlash("warning", "Selektieren Sie die Bewerber, für die Mails erstellt werden sollen, über die Checkboxen");
                return $this->redirect(['/kunde/index']);
            } /* checkBox enthält die Id */
            foreach ($checkbox as $item) {
                $IdAnrede = Kunde::findOne(['id' => $item])->geschlecht0->typus;
                $VorName = Kunde::findOne(['id' => $item])->vorname;
                $NachName = Kunde::findOne(['id' => $item])->nachname;
                $name = $VorName . " " . $NachName;
//packe die gefundenen Values in oben initialisierte Arrays
                $Geschlecht[$z] = $IdAnrede;
                $Name[$zz] = $name;
                if (empty(Kunde::findOne(['id' => $item])->email)) {
                    $session->addFlash("warning", "Für diesen Kunden exisitert im system keine Mailadresse. Legen Sie welche an!");
                    return $this->redirect(['/kunde/index']);
                } else {
                    $Mailadresses[$x] = Kunde::findOne(['id' => $item])->email;
                }
//packe die Adressen in ein Array
                $y++;
                $z++;
                $zz++;
                $x++;
            }
//übergebe die Arrays an eine Session
            $sessionPHP = Yii::$app->session;
            if (!$sessionPHP->isActive)
                $sessionPHP->open();
            $sessionPHP['adressen'] = $Mailadresses;
            $sessionPHP['name'] = $Name;
            $sessionPHP['geschlecht'] = $Geschlecht;
            if ($sessionPHP->isActive)
                $sessionPHP->close();
            if (count($checkbox) == 1)
//render das StapelOneFormular. Dazu muss jeweils das erste Element der Arrays übergeben werden
                return $this->redirect(['/mail/stapelone', 'Mailadress' => $Mailadresses[0], 'geschlecht' => $Geschlecht[0], 'name' => $Name[0]]);
            else
//render das StapelSeveralFormular
                return $this->redirect(['/mail/stapelseveral']);
        } catch (\Exception $error) {
            error_handling::error_without_id($error, KundeController::RenderBackInCaseOfError);
        }
    }

    protected function findModel($id) {
        if (($model = Kunde::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    private function message($message, $typus = 'Warnung', $delay = 1000, $type = Growl::TYPE_GROWL) {
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
