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
use yii\helpers\FileHelper;
use yii\web\Response;
use backend\models\Mail;
use app\models\MailSearch;
use frontend\models\Dateianhang;
use frontend\models\EDateianhang;
use backend\models\Mailserver;
use backend\models\LTextbaustein;
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
        $session = Yii::$app->session;
        $Zieladresse = "";
        $Ccadresse = "";
        $Bccadresse = "";
        $files = array();
        $extension = array();
        $bezeichnung = array();
        $FkInEDatei = array();
        $connection = \Yii::$app->db;
        $expression = new Expression('NOW()');
        $now = (new \yii\db\Query)->select($expression)->scalar();
        $BoolAnhang = false;
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
//Entferne das letzte Element des Arrays, sofern es ein Leerzeichen ist.
            if ($extractInner[count($extractInner) - 1] == '')
                array_pop($extractInner);
//Bilde ein Array aus dem Formularfeld der Mailadressen(cc)
            $string2Extract = $model->mail_cc;
            $extractOuter = explode("'", $string2Extract);
            $extractInnerCc = explode(";", $extractOuter[0]);
            if ($extractInnerCc[count($extractInnerCc) - 1] == '') {
                array_pop($extractInnerCc);
            }
//Bilde ein Array aus dem Formularfeld der Mailadressen(bcc)
            $string2Extract = $model->mail_bcc;
            $extractOuter = explode("'", $string2Extract);
            $extractInnerBcc = explode(";", $extractOuter[0]);
            if ($extractInnerBcc[count($extractInnerBcc) - 1] == '')
                array_pop($extractInnerBcc);

            $Ursprung = "Mainempfänger";

//Validiere alle Mailadressen
            /* Validiert die Adressen.Eine Kapselung der Logik funktioniert leider nicht,da gekapselte Methoden nicht mehr zurück rendern können */
            for ($i = 0; $i < count($extractInner); $i++) {
                if (!filter_var($extractInner[$i], FILTER_VALIDATE_EMAIL)) {
                    $message = 'Eine oder mehrere der Mailadressen im Feld ' . $Ursprung . ' ist korrupt. Bitte überprüfen Sie deren Validität und reselektieren Sie ggf. Ihre Dateianhänge';
                    $this->Ausgabe($message, 'Error', 500, Growl::TYPE_DANGER);
                    /* Dieses Codestück verhindert eine Kapselung der Logik,die demzufolge das erste mal codiert werden muss */
                    return $this->render('create', [
                                'model' => $model,
                                'modelDateianhang' => $modelDateianhang,
                                'mailFrom' => $mailFrom
                    ]);
                } else {
                    $Zieladresse = $extractInner;
                }
            }
            if (count($Zieladresse) == 1)
                $Zieladresse = $model->mail_to;
            if (!empty($model->mail_cc)) {
                $Ursprung = "Cc Empfänger";
                for ($i = 0; $i < count($extractInnerCc); $i++) {
                    if (!filter_var($extractInnerCc[$i], FILTER_VALIDATE_EMAIL)) {
                        $message = 'Eine oder mehrere der Mailadressen im Feld ' . $Ursprung . ' ist korrupt. Bitte überprüfen Sie deren Validität und reselektieren Sie ggf. Ihre Dateianhänge';
                        $this->Ausgabe($message, 'Error', 500, Growl::TYPE_DANGER);
                        /* Dieses Codestück verhindert eine Kapselung der Logik,die demzufolge bisher zweimal codiert werden musste */
                        return $this->render('create', [
                                    'model' => $model,
                                    'modelDateianhang' => $modelDateianhang,
                                    'mailFrom' => $mailFrom
                        ]);
                    } else {
                        $Ccadresse = $extractInnerCc;
                    }
                }
                if (count($Ccadresse) == 1)
                    $Ccadresse = $model->mail_cc;
            }
            if (!empty($model->mail_bcc)) {
                $Ursprung = "Bcc Empfänger";
                for ($i = 0; $i < count($extractInnerBcc); $i++) {
                    if (!filter_var($extractInnerBcc[$i], FILTER_VALIDATE_EMAIL)) {
                        $message = 'Eine oder mehrere der Mailadressen im Feld ' . $Ursprung . ' ist korrupt. Bitte überprüfen Sie deren Validität und reselektieren Sie ggf. Ihre Dateianhänge';
                        $this->Ausgabe($message, 'Error', 500, Growl::TYPE_DANGER);
                        /* Dieses Codestück verhindert eine Kapselung der Logik,die demzufolge bisher dreimal codiert werden musste */
                        return $this->render('create', [
                                    'model' => $model,
                                    'modelDateianhang' => $modelDateianhang,
                                    'mailFrom' => $mailFrom
                        ]);
                    } else {
                        $Bccadresse = $extractInnerBcc;
                    }
                }
                if (count($Bccadresse) == 1)
                    $Bccadresse = $model->mail_bcc;
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
            if ($modelDateianhang->upload($model, true)) {
// ersetze deutsche Umlaute im Dateinamen
                foreach ($modelDateianhang->attachement as $uploadedFile) {
                    $umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
                    $ersetzen = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
                    $uploadedFile->name = str_replace($umlaute, $ersetzen, $uploadedFile->name);
// lege jeweils den Dateinamen und dessen Endung in zwei unterschiedliche Arrays ab
                    array_push($files, $uploadedFile->name);
                    array_push($extension, $uploadedFile->extension);
                }
                $BoolAnhang = true;
            }
            if ($BoolAnhang && empty($modelDateianhang->l_dateianhang_art_id)) {
                $message = 'Wenn Sie einen Anhang hochladen, müssen Sie die DropDown-Box Dateianhangsart mit einem Wert belegen. Reselektieren Sie ggf. die Anhänge!';
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
            $mailAdressTo = $model->mail_to;
            $mailWurdeVerschickt = true;
            $errorAusgabe = 'Die Mail konnte nicht verschickt werden. Überprüfen Sie zunächst, ob Sie einen Mailserver initialisiert haben. Falls ja, informieren Sie den Softwarehersteller.';
            if (!$BoolAnhang) {
                if (empty($model->mail_cc) && empty($model->mail_bcc)) {
                    if ($this->SendMail($model, $Zieladresse))
                        $session->addFlash('info', "Die Mail wurde erfolgreich an $mailAdressTo  verschickt!");
                    else {
                        $session->addFlash('error', $errorAusgabe);
                        $mailWurdeVerschickt = false;
                    }
                } else if (!empty($model->mail_cc) && empty($model->mail_bcc)) {
                    $Ccadresse = $model->mail_cc;
                    if ($this->SendMail($model, $Zieladresse, $Ccadresse))
                        $session->addFlash('info', "Die Mail  wurde erfolgreich an $mailAdressTo  verschickt!Sie hat einen CC Empfänger:$Ccadresse");
                    else {
                        $session->addFlash('info', $errorAusgabe);
                        $mailWurdeVerschickt = false;
                    }
                } else if (empty($model->mail_cc) && !empty($model->mail_bcc)) {
                    $Bccadresse = $model->mail_bcc;
                    if ($this->SendMail($model, $Zieladresse, null, $Bccadresse))
                        $session->addFlash('info', "Die Mail wurde erfolgreich an $mailAdressTo erfolgreich verschickt!Sie hat einen Bcc Empfänger:$Bccadresse");
                    else {
                        $session->addFlash('info', $errorAusgabe);
                        $mailWurdeVerschickt = false;
                    }
                } else if (!empty($model->mail_cc) && !empty($model->mail_bcc)) {
                    $Ccadresse = $model->mail_cc;
                    $Bccadresse = $model->mail_bcc;
                    if ($this->SendMail($model, $Zieladresse, $Ccadresse, $Bccadresse))
                        $session->addFlash('info', "Die Mail wurde erfolgreich an $mailAdressTo verschickt!Sie hat einen Cc Empfänger:$Ccadresse und einen Bcc Empfänger:$Bccadresse");
                    else {
                        $session->addFlash('info', $errorAusgabe);
                        $mailWurdeVerschickt = false;
                    }
                }
            } else {
                if (empty($model->mail_cc) && empty($model->mail_bcc)) {
                    if ($this->SendMail($model, $Zieladresse, null, null, $files))
                        $session->addFlash('info', "Die Mail wurde erfolgreich an $mailAdressTo verschickt! Sie hatte Anhänge");
                    else {
                        $session->addFlash('info', $errorAusgabe);
                        $mailWurdeVerschickt = false;
                    }
                } else if (!empty($model->mail_cc) && empty($model->mail_bcc)) {
                    $Ccadresse = $model->mail_cc;
                    if ($this->SendMail($model, $Zieladresse, $Ccadresse, null, $files))
                        $session->addFlash('info', "Die Mail wurde erfolgreich an $mailAdressTo verschickt!Sie hat einen CC Empfänger:$Ccadresse und Anhänge");
                    else {
                        $session->addFlash('info', $errorAusgabe);
                        $mailWurdeVerschickt = false;
                    }
                } else if (empty($model->mail_cc) && !empty($model->mail_bcc)) {
                    $Bccadresse = $model->mail_bcc;
                    if ($this->SendMail($model, $Zieladresse, null, $Bccadresse, $files))
                        $session->addFlash('info', "Die Mail wurde erfolgreich an $mailAdressTo verschickt!Sie hat einen Bcc Empfänger:$Bccadresse und Anhänge");
                    else {
                        $session->addFlash('info', $errorAusgabe);
                        $mailWurdeVerschickt = false;
                    }
                } else if (!empty($model->mail_cc) && !empty($model->mail_bcc)) {
                    $Ccadresse = $model->mail_cc;
                    $Bccadresse = $model->mail_bcc;
                    if ($this->SendMail($model, $Zieladresse, $Ccadresse, $Bccadresse, $files))
                        $session->addFlash('info', "Die Mail wurde erfolgreich an $mailAdressTo verschickt!Sie hat einen Cc Empfänger:$Ccadresse und einen Bcc Empfänger:$Bccadresse und Anhänge");
                    else {
                        $session->addFlash('info', $errorAusgabe);
                        $mailWurdeVerschickt = false;
                    }
                }
            }
//Mailversand:Ende
            if ($mailWurdeVerschickt) {
// Datenbanklogik Anfang: Dazu wird eine Transaction eröffnet. Erst nach dem Commit werden die Records in die Datenbank geschrieben
                try {
                    $transaction = \Yii::$app->db->beginTransaction();
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
                        $fk = EDateianhang::findOne(['mail_id' => $model->id]);
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
            }
//Anhänge aus Verzeichnis löschen:Anfang
            try {
                if ($model->checkBoxDelete == 1 || $model->checkBoxDelete == '1') {
                    $folder = Yii::getAlias('@documentsMail');
                    if (!$this->DeleteFilesFromfolder($folder)) {
                        $session->addFlash('Warnung', "Während des Löschen der Anhangsdateien ging etwas schief. Das kann die Applikation bzgl. der Themeinitialiserung durcheinander bringen. Löschen Sie die Dateien manuell oder informieren sie den Softwarehersteller(Fehlercode:DelcFG12)");
                    }
                }
            } catch (\Exception $e) {
                error_handling::error_without_id($e, MailController::RenderBackInCaseOfError);
            }
//Anhänge aus Verzeichnis löschen:Ende
            if ($mailWurdeVerschickt)
                return $this->redirect(['view', 'id' => $model->id]);
            else
                return $this->redirect(['/mail/index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'modelDateianhang' => $modelDateianhang,
                        'mailFrom' => $mailFrom
            ]);
        }
    }

    public function actionStapelone($Mailadress, $geschlecht, $name, $id) {
        $files = array();
        $extension = array();
        $bezeichnung = array();
        $FkInEDatei = array();
        $connection = \Yii::$app->db;
        $expression = new Expression('NOW()');
        $now = (new \yii\db\Query)->select($expression)->scalar();
        $BoolAnhang = false;
        $model = new Mail();
        $modelDateianhang = new Dateianhang(['scenario' => 'create_Dateianhang']);
        $modelEDateianhang = EDateianhang::find()->all();
        $modelE = new EDateianhang();
        $mailFrom = User::findOne(Yii::$app->user->identity->id)->email;
        if ($model->loadAll(Yii::$app->request->post()) && $modelDateianhang->loadAll(Yii::$app->request->post())) {
            /* Hier muss die Versandlogik codiert werden. Der Basiscode ist in actionCreate() bereits vorhanden 
              Anfang der Modellvalidierung */
            $model->mail_to = $Mailadress;
            $Valid = $this->ValidateModels($model, $modelDateianhang);
            if (is_array($Valid)) {
                $ausgabe1 = print_r('ERROR in der Klasse ' . get_class() . '<br>');
                $ausgabe2 = var_dump($Valid);
                $ausgabe3 = 'Die Tabellen mail oder dateianhang  konnten nicht validiert werden. Informieren Sie den Softwarehersteller!(Fehlercode:ValMailsTZ455)';
                $ausgabeGesamt = $ausgabe1 . '<br>' . $ausgabe2 . '<br>' . $ausgabe3;
                var_dump($ausgabeGesamt);
                print_r('<br>');
                echo Html::a('back', ['/kunde/index'], ['title' => 'zurück']);
                die();
            }
            // Ende der Modellvalidierung 
            //Verarbeite Uploaddaten
            $modelDateianhang->attachement = UploadedFile::getInstances($modelDateianhang, 'attachement');
            if ($modelDateianhang->upload($model, true)) {
// ersetze deutsche Umlaute im Dateinamen
                foreach ($modelDateianhang->attachement as $uploadedFile) {
                    $umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
                    $ersetzen = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
                    $uploadedFile->name = str_replace($umlaute, $ersetzen, $uploadedFile->name);
// lege jeweils den Dateinamen und dessen Endung in zwei unterschiedliche Arrays ab
                    array_push($files, $uploadedFile->name);
                    array_push($extension, $uploadedFile->extension);
                }
                $BoolAnhang = true;
            }
            if ($BoolAnhang && empty($modelDateianhang->l_dateianhang_art_id)) {
                $message = 'Wenn Sie einen Anhang hochladen, müssen Sie die DropDown-Box Dateianhangsart mit einem Wert belegen. Reselektieren Sie ggf. die Anhänge!';
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
            $mailAdressTo = $model->mail_to;
            $mailWurdeVerschickt = true;
            $errorAusgabe = 'Die Mail konnte nicht verschickt werden. Überprüfen Sie zunächst, ob Sie einen Mailserver initialisiert haben. Falls ja, informieren Sie den Softwarehersteller.';
            if (!$BoolAnhang) {
                if ($this->SendMail($model, $Mailadress))
                    $session->addFlash('info', "Die Mail wurde erfolgreich an $mailAdressTo  verschickt!");
                else {
                    $session->addFlash('info', $errorAusgabe);
                    $mailWurdeVerschickt = false;
                }
            } else {
                if ($this->SendMail($model, $Mailadress, null, null, $files))
                    $session->addFlash('info', "Die Mail wurde erfolgreich an $mailAdressTo verschickt! Sie hatte Anhänge");
                else {
                    $session->addFlash('info', $errorAusgabe);
                    $mailWurdeVerschickt = false;
                }
            }
//Mailversand:Ende
            if ($mailWurdeVerschickt) {
// Datenbanklogik Anfang: Dazu wird eine Transaction eröffnet. Erst nach dem Commit werden die Records in die Datenbank geschrieben
                try {
                    $transaction = \Yii::$app->db->beginTransaction();
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
                        $fk = EDateianhang::findOne(['mail_id' => $model->id]);
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
            } else
                return $this->redirect(['/mail/index']);
        } else {
            return $this->render('stapelone', [
                        'model' => $model,
                        'modelDateianhang' => $modelDateianhang,
                        'Mailadress' => $Mailadress,
                        'geschlecht' => $geschlecht,
                        'name' => $name,
                        'id' => $id,
                        'mailFrom' => $mailFrom
            ]);
        }
    }

    public function actionStapelseveral() {
        $files = array();
        $extension = array();
        $bezeichnung = array();
        $FkInEDatei = array();
        $connection = \Yii::$app->db;
        $expression = new Expression('NOW()');
        $now = (new \yii\db\Query)->select($expression)->scalar();
        $BoolAnhang = false;
        $session = Yii::$app->session;
        $model = new Mail();
        $modelDateianhang = new Dateianhang(['scenario' => 'create_Dateianhang']);
        $modelEDateianhang = EDateianhang::find()->all();
        $modelE = new EDateianhang();
        $mailFrom = User::findOne(Yii::$app->user->identity->id)->email;
        $sessionPHP = Yii::$app->session;
        if (!$sessionPHP->isActive)
            $sessionPHP->open();
        $mailAdresses = $sessionPHP['adressen'];
        $name = $sessionPHP['name'];
        $Geschlecht = $sessionPHP['geschlecht'];
        $Ids = $sessionPHP['pkOfKunde'];
        if ($sessionPHP->isActive)
            $sessionPHP->close();
        if ($model->loadAll(Yii::$app->request->post()) && $modelDateianhang->loadAll(Yii::$app->request->post())) {
            foreach ($mailAdresses as $item) {
                $model->mail_to .= $item . ';';
            }
            $extractOuter = explode("'", $model->mail_to);
            $extractInnerCc = explode(";", $extractOuter[0]);
            array_shift($extractInnerCc);
            if ($extractInnerCc[count($extractInnerCc) - 1] == '') {
                array_pop($extractInnerCc);
            }
            $Valid = $this->ValidateModels($model, $modelDateianhang);
            if (is_array($Valid)) {
                $ausgabe1 = print_r('ERROR in der Klasse ' . get_class() . '<br>');
                $ausgabe2 = var_dump($Valid);
                $ausgabe3 = 'Die Tabellen mail oder dateianhang  konnten nicht validiert werden. Informieren Sie den Softwarehersteller!(Fehlercode:ValMailsTZ455)';
                $ausgabeGesamt = $ausgabe1 . '<br>' . $ausgabe2 . '<br>' . $ausgabe3;
                var_dump($ausgabeGesamt);
                print_r('<br>');
                echo Html::a('back', ['/kunde/index'], ['title' => 'zurück']);
                die();
            }
            // Ende der Modellvalidierung 
            //Verarbeite Uploaddaten
            $modelDateianhang->attachement = UploadedFile::getInstances($modelDateianhang, 'attachement');
            if ($modelDateianhang->upload($model, true)) {
// ersetze deutsche Umlaute im Dateinamen
                foreach ($modelDateianhang->attachement as $uploadedFile) {
                    $umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
                    $ersetzen = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
                    $uploadedFile->name = str_replace($umlaute, $ersetzen, $uploadedFile->name);
// lege jeweils den Dateinamen und dessen Endung in zwei unterschiedliche Arrays ab
                    array_push($files, $uploadedFile->name);
                    array_push($extension, $uploadedFile->extension);
                }
                $BoolAnhang = true;
            }
            if ($BoolAnhang && empty($modelDateianhang->l_dateianhang_art_id)) {
                $message = 'Wenn Sie einen Anhang hochladen, müssen Sie die DropDown-Box Dateianhangsart mit einem Wert belegen. Reselektieren Sie ggf. die Anhänge!';
                $this->Ausgabe($message, 'Warnung', 1500, Growl::TYPE_WARNING);
                return $this->render('stapelseveral', [
                            'model' => $model,
                            'modelDateianhang' => $modelDateianhang,
                            'Mailadress' => $mailAdresses,
                            'geschlecht' => $Geschlecht,
                            'name' => $name,
                            'Ids' => $Ids,
                            'mailFrom' => $mailFrom
                ]);
            }
            /* Ende der Uploadcodierung. Sofern ein Upload hochgeladen wurde, ist er in die entsprechenden Verzeichnisse integriert worden.
              Jetzt muss noch die Datenbanklogik und das Versenden der Mail codiert werden. Dazu werden die models mail,dateianhang und
              e_dateianhang benötigt. Alle wurden bereits instanziert.
             */
            //Mailversand:Anfang
            $mailAdressTo = $mailAdresses[0];
            $mailAdressBcc = $extractInnerCc;
            $mailWurdeVerschickt = true;
            $outputBcc = '';
            for ($i = 0; $i < count($mailAdressBcc); $i++) {
                if ($i < count($mailAdressBcc) - 1)
                    $outputBcc .= $mailAdressBcc[$i] . ';';
                else
                    $outputBcc .= $mailAdressBcc[$i];
            }
            $errorAusgabe = 'Die Mail konnte nicht verschickt werden. Überprüfen Sie zunächst, ob Sie einen Mailserver initialisiert haben. Falls ja, informieren Sie den Softwarehersteller.';
            if (!$BoolAnhang) {
                if ($this->SendMail($model, $mailAdressTo, NULL, $mailAdressBcc, NULL))
                    $session->addFlash('info', "Die Mail wurde erfolgreich an $mailAdressTo  verschickt! Außerdem wurde eine Blind Cardon Copy an $outputBcc verschickt");
                else {
                    $session->addFlash('info', $errorAusgabe);
                    $mailWurdeVerschickt = false;
                }
            } else {
                if ($this->SendMail($model, $mailAdressTo, NULL, $mailAdressBcc, $files))
                    $session->addFlash('info', "Die Mail wurde erfolgreich an $mailAdressTo verschickt. Außerdem wurde eine Blind Cardon Copy an $outputBcc verschickt. Sie hatte Anhänge!");
                else {
                    $session->addFlash('info', $errorAusgabe);
                    $mailWurdeVerschickt = false;
                }
            }
//Mailversand:Ende
            if ($mailWurdeVerschickt) {
// Datenbanklogik Anfang: Dazu wird eine Transaction eröffnet. Erst nach dem Commit werden die Records in die Datenbank geschrieben
                try {
                    $transaction = \Yii::$app->db->beginTransaction();
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
                        $fk = EDateianhang::findOne(['mail_id' => $model->id]);
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
            } else
                return $this->redirect(['/mail/index']);
        } else {
            return $this->render('stapelseveral', [
                        'model' => $model,
                        'modelDateianhang' => $modelDateianhang,
                        'Mailadress' => $mailAdresses,
                        'geschlecht' => $Geschlecht,
                        'name' => $name,
                        'Ids' => $Ids,
                        'mailFrom' => $mailFrom
            ]);
        }
    }

    public function actionDelete($id) {
        /* Zunächst muss dafür gesorgt werden, dass doppelt verwendete Dateien physikalisch nicht gelöscht werden. Dass muss vor dem Entfernen 
          der Records aus der Datenbank geschehen. Das eigentliche Löschen(s.u.) erfolgt dann erst, wenn die Records erfolgreich entfernt wurden */
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $path = Yii::getAlias('@documentsMail');
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
            /* Jetzt sind alle erforderlichen Arrays für das weiter unter codierte physikalische Löschen gefüllt */
            $mailHasBeenDeleted = false;
            $arrayOfFilenames = array();
            $arrayOfPkForDateiA = array();
            $session = Yii::$app->session;
            //zuerst die die Datenbankeinträge...
            if (!empty(EDateianhang::findOne(['mail_id' => $id]))) {
                $pk = EDateianhang::findOne(['mail_id' => $id])->id;
                foreach ($modelDateianhang as $item) {
                    if ($item->e_dateianhang_id == $pk) {
                        array_push($arrayOfFilenames, $item->dateiname);
                        array_push($arrayOfPkForDateiA, $item->id);
                    }
                }
                if (!empty($arrayOfPkForDateiA)) {
                    for ($i = 0; $i < count($arrayOfPkForDateiA); $i++) {
                        $this->findModelDateianhang($arrayOfPkForDateiA[$i])->delete();
                        $session->addFlash('info', "Der Mailanhang mit der Id:$arrayOfPkForDateiA[$i] wurde erfolgreich aus der Datenbank entfernt!");
                    }
                }
                $this->findModelEDateianhang($pk)->delete();
            } else {
                $this->findModel($id)->delete();
                $session->addFlash('info', "Die Mail der Id:$id wurde erfolgreich gelöscht. Sie hatte keinen Anhang!");
                $mailHasBeenDeleted = true;
            }
            if (!$mailHasBeenDeleted) {
                $this->findModel($id)->delete();
                $session->addFlash('info', "Die Mail der Id:$id wurde erfolgreich gelöscht. Sie hatte Anhänge!");
            }
            $transaction->commit();
            //...dann die Dateien physikalisch entfernen. Hier muss überprüft werden, ob Dateien doppelt genutzt werden(s.o.)
            $path_ = Yii::getAlias('@picturesBackend');
            if (!empty($arrayOfFilenames)) {
                for ($i = 0; $i < count($arrayOfFilenames); $i++) {
                    $file2BeDeleted = $path . DIRECTORY_SEPARATOR . $arrayOfFilenames[$i];
                    $file2BeDeleted_ = $path_ . DIRECTORY_SEPARATOR . $arrayOfFilenames[$i];
                    if (!file_exists($file2BeDeleted)) {
                        $session->addFlash('info', "Der physikalische Mailanhang:$arrayOfFilenames[$i] wird nicht gelöscht, da er nicht mehr exisitert!");
                    }
                    if (!file_exists($file2BeDeleted_)) {
                        $session->addFlash('info', "Der physikalische Mailanhang:$arrayOfFilenames[$i] wird aus Ihrem Webverzeichnis nicht gelöscht, da er entweder keine Bilddatei ist oder nicht mehr exisitert!");
                    }
                    if (file_exists($file2BeDeleted) && !in_array($file2BeDeleted, $arrayOfDifWithPath)) {
                        FileHelper::unlink($file2BeDeleted);
                        $session->addFlash('info', "Der physikalische Mailanhang:$arrayOfFilenames[$i] wurde erfolgreich gelöscht.");
                    } else {
                        $session->addFlash('info', "Der physikalische Mailanhang:$arrayOfFilenames[$i] wurde nicht gelöscht, da er in doppelter Verwendung war oder ist.");
                    }
                    if (file_exists($file2BeDeleted_) && !in_array($file2BeDeleted_, $arrayOfDifWithPath)) {
                        FileHelper::unlink($file2BeDeleted_);
                        $session->addFlash('info', "Der physikalische Mailanhang:$arrayOfFilenames[$i] wurde erfolgreich aus Ihrem Webverzeichnis gelöscht.");
                    } else {
                        $session->addFlash('info', "Der physikalische Mailanhang:$arrayOfFilenames[$i] wurde nicht aus Ihrem Webverzeichnis gelöscht, da er entweder keine Bilddatei oder aber in doppelter Verwendung ist.");
                    }
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            error_handling::error_without_id($e, MailController::RenderBackInCaseOfError);
        }
        return $this->redirect(['/mail/index']);
    }

    public function actionDeleteall() {
        $arrayOfPk = array();
        $model = Mail::find()->all();
        foreach ($model as $item) {
            array_push($arrayOfPk, $item->id);
        }
        if (!empty($arrayOfPk)) {
            for ($i = 0; $i < count($arrayOfPk); $i++) {
                //$arrayOfPk[$i] will be na integer, as var_dump shows up, but....
                $this->actionDelete($arrayOfPk[$i]);
            }
        } else {
            $message = 'in Ihrem Sytem sind keinerlei Mails registriert. Verschicken sie welche. Nur verschickte Mails können gelöscht werden';
            $this->Ausgabe($message, 'Info/Warnung', 2000, Growl::TYPE_WARNING);
            $searchModel = new MailSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionDeletion($id) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $session = Yii::$app->session;
            $arrayOfAnhangId = array();
            $arrayOfAnhangFilename = array();
            if (!empty(EDateianhang::findOne(['mail_id' => $id]))) {
                $pk = EDateianhang::findOne(['mail_id' => $id])->id;
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
                    $session->addFlash('info', "Der Mailanhang mit der Id:$arrayOfAnhangId[$i] wurde aus der Datenbank entfernt.");
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
                        $session->addFlash('info', "Der Mailanhang $arrayOfAnhangFilename[$i] im Ordner $frontendImg wurde physikalisch gelöscht");
                    } else
                        $session->addFlash('info', "Der Mailanhang $arrayOfAnhangFilename[$i] existiert nicht (mehr) im Ordner $frontendImg. Folglich wurde er physikalisch auch nicht gelöscht!");
                    if (file_exists($backendImg . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i])) {
                        FileHelper::unlink($backendImg . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i]);
                        $session->addFlash('info', "Der Mailanhang $arrayOfAnhangFilename[$i] im Ordner $backendImg wurde physikalisch gelöscht");
                    } else
                        $session->addFlash('info', "Der Mailanhang $arrayOfAnhangFilename[$i] existiert nicht (mehr) im Ordner $backendImg. Folglich wurde er physikalisch auch nicht gelöscht!");
                    if (file_exists($frontendDocuments . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i])) {
                        FileHelper::unlink($frontendDocuments . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i]);
                        $session->addFlash('info', "Der Mailanhang $arrayOfAnhangFilename[$i] im Ordner $frontendDocuments wurde physikalisch gelöscht");
                    } else
                        $session->addFlash('info', "Der Mailanhang $arrayOfAnhangFilename[$i] existiert nicht (mehr) im Ordner $frontendDocuments. Folglich wurde er physikalisch auch nicht gelöscht!");
                    if (file_exists($backendDocuments . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i])) {
                        FileHelper::unlink($backendDocuments . DIRECTORY_SEPARATOR . $arrayOfAnhangFilename[$i]);
                        $session->addFlash('info', "Der Mailanhang $arrayOfAnhangFilename[$i] im Ordner $backendDocuments wurde physikalisch gelöscht");
                    } else
                        $session->addFlash('info', "Der Mailanhang $arrayOfAnhangFilename[$i] existiert nicht (mehr) im Ordner $backendDocuments. Folglich wurde er physikalisch auch nicht gelöscht!");
                }
            }
        } catch (\Exception $error) {
            $transaction->rollBack();
            error_handling::error_without_id($error, MailController::RenderBackInCaseOfError);
        }
        $this->redirect(['/mail/index']);
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

    public function actionBaustein($textId) {
        $text = LTextbaustein::findOne($textId)->data;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $text;
    }

    public function actionAnhaenge($id) {
        $bmp = '/bmp/';
        $tif = '/tif/';
        $png = '/png/';
        $psd = '/psd/';
        $pcx = '/pcx/';
        $gif = '/gif/';
        $jpeg = '/jpeg/';
        $jpg = '/jpg/';
        $ico = '/ico/';
        $urlRoot = Yii::getAlias('@documentsMail') . DIRECTORY_SEPARATOR;
        $modelDateianhang = Dateianhang::find()->where(['e_dateianhang_id' => $id])->all();
        $arrayOfFilenames = array();
        //Bundle 1
        $arrayOfPics = array();
        $arrayOfBezPics = array();
        //Bundle 2
        $arrayOfOther = array();
        $arrayOfBezOther = array();
        $sessionPHP = Yii::$app->session;
        if (!$sessionPHP->isActive)
            $sessionPHP->open();
        $id = Dateianhang::findOne(['e_dateianhang_id' => $id])->id;
        foreach ($modelDateianhang as $item) {
            array_push($arrayOfFilenames, $item->dateiname);
            if (preg_match($bmp, $item->dateiname) || preg_match($tif, $item->dateiname) || preg_match($png, $item->dateiname) || preg_match($psd, $item->dateiname) || preg_match($pcx, $item->dateiname) || preg_match($gif, $item->dateiname) || preg_match($jpeg, $item->dateiname) || preg_match($jpg, $item->dateiname) || preg_match($ico, $item->dateiname)) {
                array_push($arrayOfBezPics, $item->bezeichnung);
            } else
                array_push($arrayOfBezOther, $item->bezeichnung);
        }
        for ($i = 0; $i < count($arrayOfFilenames); $i++) {
            if (preg_match($bmp, $arrayOfFilenames[$i]) || preg_match($tif, $arrayOfFilenames[$i]) || preg_match($png, $arrayOfFilenames[$i]) || preg_match($psd, $arrayOfFilenames[$i]) || preg_match($pcx, $arrayOfFilenames[$i]) || preg_match($gif, $arrayOfFilenames[$i]) || preg_match($jpeg, $arrayOfFilenames[$i]) || preg_match($jpg, $arrayOfFilenames[$i]) || preg_match($ico, $arrayOfFilenames[$i]))
                array_push($arrayOfPics, $arrayOfFilenames[$i]);
            else
                array_push($arrayOfOther, $arrayOfFilenames[$i]);
        }
        if (!$sessionPHP->isActive)
            $sessionPHP->open();
        $sessionPHP['bilder'] = $arrayOfPics;
        $sessionPHP->close();
        if (count($arrayOfPics) > 0 && count($arrayOfOther) == 0) {
            for ($i = 0; $i < count($arrayOfPics); $i++) {
                copy($urlRoot . $arrayOfPics[$i], Yii::getAlias('@picturesBackend') . DIRECTORY_SEPARATOR . $arrayOfPics[$i]);
            }
            return $this->render('_form_mailanhaenge', [
                        'arrayOfPics' => $arrayOfPics,
                        'arrayOfBezPics' => $arrayOfBezPics,
                        'id' => $id
            ]);
        } else if (count($arrayOfPics) == 0 && count($arrayOfOther) > 0)
            return $this->render('_form_mailanhaenge', [
                        'arrayOfOther' => $arrayOfOther,
                        'arrayOfBezOther' => $arrayOfBezOther,
                        'id' => $id
            ]);
        else if (count($arrayOfPics) > 0 && count($arrayOfOther) > 0) {
            for ($i = 0; $i < count($arrayOfPics); $i++) {
                if (preg_match($bmp, $arrayOfPics[$i]) || preg_match($tif, $arrayOfPics[$i]) || preg_match($png, $arrayOfPics[$i]) || preg_match($psd, $arrayOfPics[$i]) || preg_match($pcx, $arrayOfPics[$i]) || preg_match($gif, $arrayOfPics[$i]) || preg_match($jpeg, $arrayOfPics[$i]) || preg_match($jpg, $arrayOfPics[$i]) || preg_match($ico, $arrayOfPics[$i])) {
                    copy($urlRoot . $arrayOfPics[$i], Yii::getAlias('@picturesBackend') . DIRECTORY_SEPARATOR . $arrayOfPics[$i]);
                }
            }

            return $this->render('_form_mailanhaenge', [
                        'arrayOfPics' => $arrayOfPics,
                        'arrayOfBezPics' => $arrayOfBezPics,
                        'arrayOfOther' => $arrayOfOther,
                        'arrayOfBezOther' => $arrayOfBezOther,
                        'id' => $id
            ]);
        }
    }

    public function actionDocument($id) {
        $filePath = Yii::getAlias('@documentsMail');
        $completePath = $filePath . DIRECTORY_SEPARATOR . $id;
        if (file_exists($completePath))
            return Yii::$app->response->sendFile($completePath, $id);
        else {
            $session = Yii::$app->session;
            $session->addFlash('info', "Der Mailanhang ist zwar in der Datenbank registriert, befindet sich jedoch physikalisch nicht mehr auf Ihrem Webserver. Löschen Sie den Anhang über das entsprechende Icon!");
            return $this->redirect(['/mail/index']);
        }
    }

    //gekapselte Methoden

    private function findModel($id) {
        if (($model = Mail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', "Das Model Mail mit der Id:$id konnte nicht geladen werden. Informieren Sie den Softwarehersteller"));
        }
    }

    private function findModelDateianhang($id) {
        if (($model = Dateianhang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', "Das Model Dateianhang mit der Id:$id konnte nicht geladen werden. Informieren Sie den Softwarehersteller"));
        }
    }

    private function findModelEDateianhang($id) {
        if (($model = EDateianhang::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', "Das Model EDateianhang mit der Id:$id konnte nicht geladen werden. Informieren Sie den Softwarehersteller"));
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

    private function FetchMailServerData() {
        try {
            $checkServerConf = Mailserver::find()->count('id');
            if ($checkServerConf < 1)
                return false;
            $serverId = Mailserver::find()->min('id');
            $host = Mailserver::findOne(['id' => $serverId])->serverHost;
            $username = Mailserver::findOne(['id' => $serverId])->username;
            $password = Mailserver::findOne(['id' => $serverId])->password;
            $port = Mailserver::findOne(['id' => $serverId])->port;
            $useEncryption = Mailserver::findOne(['id' => $serverId])->useEncryption;
            if ($useEncryption == 1)
                $encryption = Mailserver::findOne(['id' => $serverId])->encryption;
            else
                $encryption = null;
            if ($encryption != null)
                Yii::$app->mailer->setTransport([
                    'class' => 'Swift_SmtpTransport',
                    'host' => $host,
                    'username' => $username,
                    'password' => $password,
                    'port' => $port,
                    'encryption' => $encryption
                ]);
            else
                Yii::$app->mailer->setTransport([
                    'class' => 'Swift_SmtpTransport',
                    'host' => $host,
                    'username' => $username,
                    'password' => $password,
                    'port' => $port
                ]);
            return Yii::$app->mailer;
        } catch (yii\db\Exception $e) {
            error_handling::error_without_id($e, MailController::RenderBackInCaseOfError);
        }
    }

    private function SendMail($model, $zielAdresse, $Cc = NULL, $Bcc = NULL, $attachementFiles = NULL) {
        $SendObject = $this->FetchMailServerData();
        if (!$SendObject)
            return false;
        try {
            if (is_array($attachementFiles))
                $LocalDirectory = Yii::getAlias('@documentsMail') . DIRECTORY_SEPARATOR;
            /* Da es 2^3 Möglichkeiten gibt, muss es auch 2^3 Konditionen geben. Auf ein Switch/Case-Konstrukt wird hier verzichtet. Außerdem 
              wird daurauf verzichtet, eventuelle Vereinfachungen zu implementieren, damit der Code einfacher wartbar ist. Folgende boolsche
              Gleichung liefert die Basis-> A: MailAdresse Cc B: MailAdresse Bcc C: Anhang
              (1)Y=notA&&notB&&notC
              (2)Y=A&&notB&&notC
              (3)Y=notA&&B&&notC
              (4)Y=A&&B&&notC
              (5)Y=notA&&notB&&C
              (6)Y=A&&notB&&C
              (7)Y=notA&&B&&C
              (8)Y=A&&B&&C
             */


            /* Mail hat keinen Anhang (1) bis(4) */
//(1)
            if ($Cc == NULL && $Bcc == NULL && $attachementFiles == NULL) {
                $SendObject = Yii::$app->mailer->compose()
                        ->setFrom($model->mail_from)
                        ->setTo($zielAdresse)
                        ->setSubject($model->betreff)
                        ->setHtmlBody($model->bodytext)
                        ->setTextBody($model->bodytext)
                        ->send();
//(2)
            } else if ($Cc != NULL && $Bcc == NULL && $attachementFiles == NULL) {
                $SendObject = Yii::$app->mailer->compose()
                        ->setFrom($model->mail_from)
                        ->setTo($zielAdresse)
                        ->setCc($Cc)
                        ->setSubject($model->betreff)
                        ->setHtmlBody($model->bodytext)
                        ->setTextBody($model->bodytext)
                        ->send();
//(3)
            } else if ($Cc == NULL && $Bcc != NULL && $attachementFiles == NULL) {
                $SendObject = Yii::$app->mailer->compose()
                        ->setFrom($model->mail_from)
                        ->setTo($zielAdresse)
                        ->setBcc($Bcc)
                        ->setSubject($model->betreff)
                        ->setHtmlBody($model->bodytext)
                        ->setTextBody($model->bodytext)
                        ->send();
//(4)
            } else if ($Cc != NULL && $Bcc != NULL && $attachementFiles == NULL) {
                $SendObject = Yii::$app->mailer->compose()
                        ->setFrom($model->mail_from)
                        ->setTo($zielAdresse)
                        ->setCc($Cc)
                        ->setBcc($Bcc)
                        ->setSubject($model->betreff)
                        ->setHtmlBody($model->bodytext)
                        ->setTextBody($model->bodytext)
                        ->send();
                /* Mail hat Anhang(5) bis (8) */
//(5)
            } else if ($Cc == NULL && $Bcc == NULL && $attachementFiles != NULL) {
                $SendObject = Yii::$app->mailer->compose()
                        ->setFrom($model->mail_from)
                        ->setTo($zielAdresse)
                        ->setSubject($model->betreff)
                        ->setHtmlBody($model->bodytext)
                        ->setTextBody($model->bodytext);
                foreach ($attachementFiles as $file) {
                    $SendObject->attach($LocalDirectory . $file);
                }
                $SendObject->send();
//(6)
            } else if ($Cc != NULL && $Bcc == NULL && $attachementFiles != NULL) {
                $SendObject = Yii::$app->mailer->compose()
                        ->setFrom($model->mail_from)
                        ->setTo($zielAdresse)
                        ->setCc($Cc)
                        ->setSubject($model->betreff)
                        ->setHtmlBody($model->bodytext)
                        ->setTextBody($model->bodytext);
                foreach ($attachementFiles as $file) {
                    $SendObject->attach($LocalDirectory . $file);
                }
                $SendObject->send();
//(7)
            } else if ($Cc == NULL && $Bcc != NULL && $attachementFiles != NULL) {
                $SendObject = Yii::$app->mailer->compose()
                        ->setFrom($model->mail_from)
                        ->setTo($zielAdresse)
                        ->setBcc($Bcc)
                        ->setSubject($model->betreff)
                        ->setHtmlBody($model->bodytext)
                        ->setTextBody($model->bodytext);
                foreach ($attachementFiles as $file) {
                    $SendObject->attach($LocalDirectory . $file);
                }
                $SendObject->send();
//(8)
            } else if ($Cc != NULL && $Bcc != NULL && $attachementFiles != NULL) {
                $SendObject = Yii::$app->mailer->compose()
                        ->setFrom($model->mail_from)
                        ->setTo($zielAdresse)
                        ->setCc($Cc)
                        ->setBcc($Bcc)
                        ->setSubject($model->betreff)
                        ->setHtmlBody($model->bodytext)
                        ->setTextBody($model->bodytext);
                foreach ($attachementFiles as $file) {
                    $SendObject->attach($LocalDirectory . $file);
                }
                $SendObject->send();
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function DeleteFilesFromfolder($folder) {
        try {
            $session = Yii::$app->session;
            $arrayOfFiles = FileHelper::findFiles($folder);
            foreach ($arrayOfFiles as $item) {
                if (!preg_match('/ignore/', $item)) {
                    FileHelper::unlink($item);
                    $session->addFlash('info', "Der Mailanhang:$item wurde erfolgreich aus dem Verzeichnis entfernt");
                }
            }
            return true;
        } catch (\Exception $e) {
            return false;
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

}
