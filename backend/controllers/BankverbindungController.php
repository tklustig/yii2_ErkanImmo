<?php

namespace backend\controllers;

use Yii;
use backend\models\Bankverbindung;
use backend\models\BankverbindungSearch;
use frontend\models\Kunde;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use yii\base\DynamicModel;
use kartik\widgets\Growl;
use kartik\widgets\Alert;
use common\classes\error_handling;

class BankverbindungController extends Controller {

    const RenderBackInCaseOfError = '/site/index';

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $countBankverbindung = Bankverbindung::find()->count('id');
        if ($countBankverbindung == 0) {
            $session = Yii::$app->session;
            $session->addFlash('info', 'Es exisitieren noch keine Bankverbindungen in der Datenbank. Steigern Sie Ihre Kundenaqkuise oder hinterlegen Sie deren Bankdaten!');
            return $this->redirect(['/site/index']);
        }
        $searchModel = new BankverbindungSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        /* print_r('<br><br>');
          var_dump(Yii::$app->request->queryParams);
          print_r('Der Parameter kunde_id muss im Searchmodel entsprechend ausgewertet werden'); */
        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionView($id) {
        $model = $this->findModel($id);
        $providerKunde = new \yii\data\ArrayDataProvider([
            'allModels' => $model->kundes,
        ]);
        return $this->render('view', ['model' => $this->findModel($id), 'providerKunde' => $providerKunde]);
    }

    public function actionCreate($id) {
        try {
            $model = new Bankverbindung(['scenario' => 'create_Bankverbindung']);
            $wrongInput = false;
            if ($model->loadAll(Yii::$app->request->post())) {
                //Sofern jquerySubmitButton gedrückt
                if (Yii::$app->request->post('submit') != null && Yii::$app->request->post('submit') == 'submitIbanData') {
                    if (!empty($model->iban) && !empty($model->bic)) {
                        if (strlen($model->bic) != 11 || strlen($model->iban) != 22)
                            $wrongInput = true;
                    }
                    if (!$wrongInput) {
                        if (!empty($model->institut) && !empty($model->laenderkennung)) {
                            $laenderkennung = $model->laenderkennung;
                            $institut = $model->institut;
                            $bic = $model->bic;
                            $iban = $model->iban;
                            $blz = substr($iban, 4, 8);
                            $lengthBlz = strlen($blz) + 4;
                            $lengthIban = strlen($iban);
                            $kontonummer = substr($iban, $lengthBlz, $lengthIban);
                            if (!is_numeric($kontonummer) || !is_numeric($blz)) {
                                $message = "Die BIC und/oder die IBAN haben das falsche Format. Die errechneten BLZ bzw. Kontonr. dürfen nur Zahlen enthalten.<br>BLZ:$blz<br>Kontonr.:$kontonummer";
                                $this->message($message, 'Warnung', 1500, Growl::TYPE_WARNING);
                                return $this->render('create', ['model' => $model, 'id' => $id]);
                            }
                            return $this->redirect(['conclusion', 'id' => $id, 'laenderkennung' => $laenderkennung, 'kontonummer' => $kontonummer, 'blz' => $blz, 'institut' => $institut, 'bic' => $bic, 'iban' => $iban]);
                        } else {
                            $message = 'Bitte geben Sie die Länderkennung und das Instutut an, damit der Prozess weitergeführt werden kann. Nutzen Sie ggf. die Haupteingabeoption!';
                            $this->message($message, 'Warnung', 1500, Growl::TYPE_GROWL);
                            return $this->render('create', ['model' => $model, 'id' => $id]);
                        }
                    } else if ($wrongInput) {
                        $message = 'Die BIC und/oder die IBAN haben die falsche Länge. Die BIC muss genau 8-stellig, die IBAN 22-stellig sein.';
                        $this->message($message, 'Warnung', 1500, Growl::TYPE_WARNING);
                        return $this->render('create', ['model' => $model, 'id' => $id]);
                    }
                }
                //Sofern HauptSubmitButton gedrückt
                $laenderkennung = $model->laenderkennung;
                $kontonummer = $model->kontoNr;
                $blz = $model->blz;
                if (empty($laenderkennung) || empty($blz) || empty($kontonummer)) {
                    $message = 'Sie müssen alle erforderlichen Daten eingeben, damit der Prozess weitergeführt werden kann.';
                    $this->message($message, 'Warnung', 1500, Growl::TYPE_GROWL);
                    return $this->render('create', ['model' => $model, 'id' => $id]);
                }
                $curl = curl_init();
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://fintechtoolbox.com/bankcodes/' . $blz));
                try {
                    $webserviceValues = curl_exec($curl);
                    $respobj = json_decode($webserviceValues);
                    $institut = $respobj->bank_code->description . ' ' . $respobj->bank_code->city;
                    $bic = $respobj->bank_code->bic;
                    curl_close($curl);
                } catch (\Exception $e) {
                    $message = 'Mindestens eine Ihrer Angaben sind inkorrekt. Bitte überprüfen Sie, ob Kontonummer und Bankleitzahl stimmig sind!';
                    $this->message($message, 'Error', 250, Growl::TYPE_DANGER);
                    $zusatz = 'Um zu überprüfen, ob der Webservice die korrekten Daten errechnet, geben Sie bitte folgendes ein:<br>für Länderkennung: DE<br>für BLZ: 25050180<br>für Kontonummer: 1911869221';
                    echo Alert::widget([
                        'type' => Alert::TYPE_INFO,
                        'title' => 'Importan Message',
                        'icon' => 'fas fa-info-circle',
                        'body' => $zusatz,
                        'showSeparator' => true,
                        'delay' => false
                    ]);
                    return $this->render('create', ['model' => $model, 'id' => $id]);
                }
                if (substr($blz, -1) == ' ')
                    $blz = substr($blz, 0, -1);
                else if (substr($kontonummer, -1) == ' ')
                    $kontonummer = substr($kontonummer, 0, -1);

                $iban = $this->CalcIban($laenderkennung, $blz, $kontonummer, $model, $id);
                /* IBAN(22)=Ländernummer(2)+Prüfziffer(2)+BLZ(8)+KontoNr.(max.10)= DE92250501801911869221 =>
                  BLZ:=25050180 / KontoNr.:=1911869221 / BIC:=SPKHDE2HXXX */
                if (!$iban) {
                    $message = 'IbanRaw hat in der gekapselten Methode CalcIban() die falsche Länge. Informieren Sie den Softwarehersteller oder überprüfen Sie Ihre Eingaben.';
                    $this->message($message, 'Error!', 250, Growl::TYPE_GROWL);
                    return $this->render('create', ['model' => $model, 'id' => $id,]);
                } else
                    return $this->redirect(['conclusion', 'id' => $id, 'laenderkennung' => $laenderkennung, 'kontonummer' => $kontonummer, 'blz' => $blz, 'institut' => $institut, 'bic' => $bic, 'iban' => $iban]);
            } else
                return $this->render('create', ['model' => $model, 'id' => $id]);
        } catch (\Exception $error) {
            error_handling::error_without_id($error, BankverbindungController::RenderBackInCaseOfError);
        }
    }

    public function actionConclusion($id, $laenderkennung, $kontonummer, $blz, $institut, $bic, $iban) {
        $transaction = Yii::$app->db->beginTransaction();
        $arrayOfBank = array();
        $model = new Bankverbindung();
        $session = Yii::$app->session;
        $modelBankExisting = Bankverbindung::find()->all();
        foreach ($modelBankExisting as $item) {
            array_push($arrayOfBank, $item->blz);
            array_push($arrayOfBank, $item->kontoNr);
        }
        try {
            if (Yii::$app->request->post()) {
                if (in_array($blz, $arrayOfBank) && in_array($kontonummer, $arrayOfBank)) {                  
                    $message = "Die Blz $blz und die Kontonummer $kontonummer wurden bereits einem anderen Kunden zugewiesen. Diese Applikation erlaubt nicht die Verwendung derselben Bankdaten für mehrere Personen.<br>Wiederholen Sie den Vorgang ggf. mit anderen Bankdaten, oder ändern Sie bestehende ab!";
                    $session->addFlash('info', $message);
                    return $this->redirect(['/site/index']);
                }
                $model->laenderkennung = $laenderkennung;
                $model->institut = $institut;
                $model->blz = $blz;
                $model->kontoNr = $kontonummer;
                $model->iban = $iban;
                $model->bic = $bic;
                $model->kunde_id = $id;
                $model->save();
                $connection = Yii::$app->db;
                $connection->createCommand()
                        ->update('kunde', ['bankverbindung_id' => $model->id], ['id' => $id])
                        ->execute();
                $session->addFlash('info', "Die Bankdaten wurden Ihrem System unter der ID:$model->id neu hinzugefügt!");
            } else {
                return $this->render('_form_conclusion', [
                            'model' => $model,
                            'id' => $id,
                            'laenderkennung' => $laenderkennung,
                            'kontonummer' => $kontonummer,
                            'blz' => $blz,
                            'institut' => $institut,
                            'bic' => $bic,
                            'iban' => $iban
                ]);
            }
        } catch (\Exception $error) {
            error_handling::error_without_id($error, BankverbindungController::RenderBackInCaseOfError);
        }
        $transaction->commit();
        return $this->redirect(['/site/index']);
    }

    public function actionUpdate($id) {
        if (Yii::$app->request->post('_asnew') == '1') {
            $model = new Bankverbindung();
        } else {
            $model = $this->findModel($id);
        }

        if ($model->loadAll(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    public function actionDelete($id) {
        $session = Yii::$app->session;
        $this->findModel($id)->delete();
        $session->addFlash('info', "Die Bankdaten der ID:$id wurden aus Ihrem System entfernt!");
        return $this->redirect(['/site/index']);
    }

    public function actionPdf($id) {
        $model = $this->findModel($id);
        $providerKunde = new \yii\data\ArrayDataProvider([
            'allModels' => $model->kundes,
        ]);

        $content = $this->renderAjax('_pdf', ['model' => $model, 'providerKunde' => $providerKunde]);

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
        if (($model = Bankverbindung::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    protected function findModelKunde($id) {
        if (($model = Kunde::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionSelect() {
        $modelKunde = Kunde::find()->all();
        if (!empty($modelKunde)) {
            $this->layout = 'main_immo';
            $DynamicModel = new DynamicModel(['kunde']);
            $DynamicModel->addRule(['kunde'], 'integer');
            $DynamicModel->addRule(['kunde'], 'required');

            if ($DynamicModel->load(Yii::$app->request->post()))
                $this->redirect(['/bankverbindung/create', 'id' => $DynamicModel->kunde]);
            else
                return $this->render('_form_select', ['DynamicModel' => $DynamicModel]);
        } else {
            $session = Yii::$app->session;
            $session->addFlash('info', 'Es exisitert noch kein Kunde in der Datenbank. Steigern Sie Ihre Kundenaqkuise!');
            return $this->redirect(['site/index']);
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

    private function CalcIban($laenderkennung, $bankleitzahl, $kontonummer) {
        try {
            $laenderkennungTranslate = false;
            while (strlen($kontonummer) < 10) {
                $kontonummer = '0' . $kontonummer;
            }
            $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $landExplode = str_split($laenderkennung);
            if (count($landExplode) == 2) {
                foreach ($landExplode as $check) {
                    $laenderkennungTranslate .= (string) strpos($alphabet, $check) + 10;
                }
                $laenderkennungTranslate = $laenderkennungTranslate . '00';
                $ibanRaw = $bankleitzahl . $kontonummer . $laenderkennungTranslate;
                if (strlen($ibanRaw) == 24) {
                    $pruefsumme = 98 - bcmod($ibanRaw, 97);
                    if ($pruefsumme < 10)
                        $pruefnummer = '0' . $pruefsumme;
                    $iban = $laenderkennung . $pruefsumme . $bankleitzahl . $kontonummer;
                    return $iban;
                } else
                    return false;
            } else {
                print_r('Error!Error!Error<br>Länderkennung hat das falsche Format.<br>Informieren Sie den Softwarehersteller');
                die();
            }
        } catch (\Exception $error) {
            error_handling::error_without_id($error, BankverbindungController::RenderBackInCaseOfError);
        }
    }

}
