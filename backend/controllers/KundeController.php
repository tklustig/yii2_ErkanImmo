<?php

namespace backend\controllers;

use Yii;
use frontend\models\Kunde;
use backend\models\KundeSearch;
use frontend\models\LPlz;
use backend\models\Bankverbindung;
use frontend\models\Dateianhang;
use frontend\models\EDateianhang;
use frontend\models\Kundeimmobillie;
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
            $session = Yii::$app->session;
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
        $model = $this->findModel($id);
        $plzId = $model->l_plz_id;
        $modelDateianhang = new Dateianhang(['scenario' => 'create_Dateianhang']);
        $modelE = new EDateianhang();
        $modelBank = Bankverbindung::find()->all();
        $arrayOfBankPk = array();
        $arrayOfDoubleBankPk = array();
        foreach ($modelBank as $item) {
            array_push($arrayOfBankPk, $item->id);
        }
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
        if (!empty(Bankverbindung::findOne(['id' => $model->bankverbindung_id])))
            $hasBankConnection = true;
        else
            $hasBankConnection = false;
        //var_dump($hasBankConnection);
        if ($model->loadAll(Yii::$app->request->post()) && $modelDateianhang->loadAll(Yii::$app->request->post())) {
            //den Plzstring in die Id zurück verwandeln
            $plzID = LPlz::findOne(['plz' => $model->lPlz->plz])->id;
            $model->l_plz_id = $plzID;
            //den Bankstring in die Id zurück verwandeln      
            $modelDateianhang->attachement = UploadedFile::getInstances($modelDateianhang, 'attachement');
            if ($modelDateianhang->uploadBackend($modelDateianhang))
                $BoolAnhang = true;
            if ($BoolAnhang && empty($modelDateianhang->l_dateianhang_art_id)) {
                $ausgabe = 'Wenn Sie einen Anhang hochladen, müssen Sie die DropDown-Box Dateianhangsart mit einem Wert belegen. Das soeben hochgeladene Kundenbild wurde wieder entfernt. Reselektieren Sie es ggf.';
                $this->message($ausgabe, 'Warnung', 1250, Growl::TYPE_WARNING);
                foreach ($modelDateianhang->attachement as $uploadedFile) {
                    if (file_exists(Yii::getAlias('@picturesBackend') . DIRECTORY_SEPARATOR . $uploadedFile->name))
                        FileHelper::unlink(Yii::getAlias('@picturesBackend') . DIRECTORY_SEPARATOR . $uploadedFile->name);
                }
                return $this->render('update', [
                            'model' => $model,
                            'plz' => $plz,
                            'id' => $id,
                            'modelDateianhang' => $modelDateianhang
                ]);
            }
            foreach ($modelDateianhang->attachement as $uploadedFile) {
                $umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
                $ersetzen = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
// lege jede den Dateinamen  in das Array ab
                array_push($files, str_replace($umlaute, $ersetzen, $uploadedFile->name));
            }
            $valid = $model->validate();
            $IsValid = $modelDateianhang->validate() && $valid;
            if ($IsValid) {
                $transaction = \Yii::$app->db->beginTransaction();
                $model->save();
                if (!$hasBankConnection) {
                    $arrayOfBankKunde = array();
                    $arrayOfBankKunde = $arrayOfBankPk;
                    array_push($arrayOfBankKunde, $model->bankverbindung_id);
                    $unique = array_unique($arrayOfBankKunde);
                    $arrayOfDoubleBankPk = array_diff_assoc($arrayOfBankKunde, $unique);
                }
                if (!empty($arrayOfDoubleBankPk)) {
                    $transaction->rollBack();
                    $ausgabe = 'Diese Bankverbindung ist bereits einem anderen Kunden zugeordnet. Diese Applikation versucht, Mißbrauch zu verhindern!';
                    $this->message($ausgabe);
                    return $this->render('update', [
                                'model' => $model,
                                'plz' => $plz,
                                'id' => $id,
                                'modelDateianhang' => $modelDateianhang
                    ]);
                }
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
                }
                if (in_array($model->id, $FkInEDatei))
                    $fk = EDateianhang::findOne(['kunde_id' => $model->id])->id;
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
                        'id' => $id,
                        'modelDateianhang' => $modelDateianhang
            ]);
        }
    }

    public function actionDelpic($id) {
        $session = Yii::$app->session;
        try {
            $transaction = Yii::$app->db->beginTransaction();
            $path = Yii::getAlias('@picturesBackend');
            $pkOfDateianhang = Dateianhang::findOne(['e_dateianhang_id' => $id])->id;
            $filename = Dateianhang::findOne(['id' => $pkOfDateianhang])->dateiname;
            if (file_exists($path . DIRECTORY_SEPARATOR . $filename)) {
                FileHelper::unlink($path . DIRECTORY_SEPARATOR . $filename);
                $session->addFlash('info', "Das Kundenbild $filename wurde physikalisch aus Ihrem Webverzeichnis gelöscht");
            } else
                $session->addFlash('info', "Das Kundenbild $filename konnte physikalisch nicht gelöscht werden, da es nicht mehr exisitert!");
            $this->findModelDateianhang($pkOfDateianhang)->delete();
            $this->findModelEDateianhang($id)->delete();
            $transaction->commit();
            $session->addFlash('info', "Das Kundenbild mit der Id $pkOfDateianhang wurde aus Ihrer Datenbank entfernt");
        } catch (\Exception $error) {
            $transaction->rollBack();
            error_handling::error_without_id($error, KundeController::RenderBackInCaseOfError);
        }
        return $this->redirect(['/site/index']);
    }

    public function actionDelete($id) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $session = Yii::$app->session;
            $pkOfKundeImmo = Kundeimmobillie::findOne(['kunde_id' => $id])->id;
            $this->findModelKundeImmo($pkOfKundeImmo)->delete();
            $this->findModel($id)->delete();
            $session->addFlash('info', "Der Kunde mit der Id:$id wurde aus der Datenbank entfernt!");
        } catch (IntegrityException $er) {
            $message = "Der Kunde mit der Id:$id kann nicht gelöscht werden, da das gegen die referentielle Integrität verstößt. Löschen Sie zunächst die korrespondierenden Bankdaten, Besichtigungstermine und Bilder!";
            $this->message($message, 'Error', 1500, Growl::TYPE_DANGER);
            $searchModel = new KundeSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
        $transaction->commit();
        return $this->redirect(['/site/index']);
    }

    public function actionDeletion($id) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $session = Yii::$app->session;
            $arrayOfAnhangId = array();
            $arrayOfAnhangFilename = array();
            if (!empty(EDateianhang::findOne(['kunde_id' => $id]))) {
                $pk = EDateianhang::findOne(['kunde_id' => $id])->id;
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
                    $session->addFlash('info', "Der Kundenanhang mit der Id:$arrayOfAnhangId[$i] wurde aus der Datenbank entfernt.");
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
                        $session->addFlash('info', "Der Kundenanhang $arrayOfAnhangFilename[$i] im Ordner $frontendImg wurde physikalisch gelöscht");
                    } else
                        $session->addFlash('info', "Der Kundenanhang $arrayOfAnhangFilename[$i] existiert nicht (mehr) im Ordner $frontendImg. Folglich wurde er physikalisch auch nicht gelöscht!");
                    if (file_exists($backendImg . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i])) {
                        FileHelper::unlink($backendImg . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i]);
                        $session->addFlash('info', "Der Kundenanhang $arrayOfAnhangFilename[$i] im Ordner $backendImg wurde physikalisch gelöscht");
                    } else
                        $session->addFlash('info', "Der Kundenanhang $arrayOfAnhangFilename[$i] existiert nicht (mehr) im Ordner $backendImg. Folglich wurde er physikalisch auch nicht gelöscht!");
                    if (file_exists($frontendDocuments . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i])) {
                        FileHelper::unlink($frontendDocuments . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i]);
                        $session->addFlash('info', "Der Kundenanhang $arrayOfAnhangFilename[$i] im Ordner $frontendDocuments wurde physikalisch gelöscht");
                    } else
                        $session->addFlash('info', "Der Kundenanhang $arrayOfAnhangFilename[$i] existiert nicht (mehr) im Ordner $frontendDocuments. Folglich wurde er physikalisch auch nicht gelöscht!");
                    if (file_exists($backendDocuments . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i])) {
                        FileHelper::unlink($backendDocuments . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i]);
                        $session->addFlash('info', "Der Kundenanhang $arrayOfAnhangFilename[$i] im Ordner $backendDocuments wurde physikalisch gelöscht");
                    } else
                        $session->addFlash('info', "Der Kundenanhang $arrayOfAnhangFilename[$i] existiert nicht (mehr) im Ordner $backendDocuments. Folglich wurde er physikalisch auch nicht gelöscht!");
                }
            }
        } catch (\Exception $error) {
            $transaction->rollBack();
            error_handling::error_without_id($error, KundeController::RenderBackInCaseOfError);
        }
        $this->redirect(['/kunde/index']);
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
        $session = Yii::$app->session;
        $Mailadresses = array();
        $Name = array();
        $Geschlecht = array();
        $pkA = array();
        /* Indizie für Adressen */
        $x = 0;
        /* Indizie für Geschlecht */
        $z = 0;
        /* Indizie für Namen */
        $y = 0;
        /* Indizie für Primärschlüssel */
        $xyz = 0;
        try {
            $checkbox = (array) Yii::$app->request->post('selection');
            if (empty(($checkbox)) && (isset($_POST['button_checkBoxes']))) {
                $session->addFlash("warning", "Selektieren Sie die Kunden, für die Mails erstellt werden sollen, über die Checkboxen");
                return $this->redirect(['/kunde/index']);
            } /* checkBox enthält die Id */
            foreach ($checkbox as $item) {
                $IdAnrede = Kunde::findOne(['id' => $item])->geschlecht0->typus;
                $VorName = Kunde::findOne(['id' => $item])->vorname;
                $NachName = Kunde::findOne(['id' => $item])->nachname;
                $pk = Kunde::findOne(['id' => $item])->id;
                $name = $VorName . " " . $NachName;
//packe die gefundenen Values in oben initialisierte Arrays
                $Geschlecht[$z] = $IdAnrede;
                $Name[$y] = $name;
                $pkA[$xyz] = $pk;
                if (empty(Kunde::findOne(['id' => $item])->email)) {
                    $session->addFlash("warning", "Für diesen Kunden exisitert im system keine Mailadresse. Legen Sie welche an!");
                    return $this->redirect(['/kunde/index']);
                } else {
                    $Mailadresses[$x] = Kunde::findOne(['id' => $item])->email;
                }
//packe die Adressen in ein Array
                $y++;
                $z++;
                $x++;
                $xyz++;
            }
//übergebe die Arrays an eine Session
            $sessionPHP = Yii::$app->session;
            if (!$sessionPHP->isActive)
                $sessionPHP->open();
            $sessionPHP['adressen'] = $Mailadresses;
            $sessionPHP['name'] = $Name;
            $sessionPHP['geschlecht'] = $Geschlecht;
            $sessionPHP['pkOfKunde'] = $pkA;
            if ($sessionPHP->isActive)
                $sessionPHP->close();
            if (count($checkbox) == 1)
//render das StapelOneFormular. Dazu muss jeweils das erste Element der Arrays übergeben werden
                return $this->redirect(['/mail/stapelone', 'Mailadress' => $Mailadresses[0], 'geschlecht' => $Geschlecht[0], 'name' => $Name[0], 'id' => $pkA[0]]);
            else
//render das StapelSeveralFormular
                return $this->redirect(['/mail/stapelseveral']);
        } catch (\Exception $error) {
            error_handling::error_without_id($error, KundeController::RenderBackInCaseOfError);
        }
    }

    public function actionAuswahlk($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new yii\db\Query();
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
        if (($model = Kunde::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    protected function findModelDateianhang($id) {
        if (($model = Dateianhang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Das Model Dateianhang konnte nicht gealden werden. Informieren Sie den Softwarehersteller'));
        }
    }

    protected function findModelEDateianhang($id) {
        if (($model = EDateianhang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Das Model EDateianhang konnte nicht gealden werden. Informieren Sie den Softwarehersteller'));
        }
    }

    protected function findModelKundeImmo($id) {
        if (($model = Kundeimmobillie::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Das Model KundeImmobilie konnte nicht geladen werden. Informieren Sie den Softwarehersteller'));
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

}
