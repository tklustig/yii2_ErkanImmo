<?php

namespace backend\controllers;

use Yii;
use backend\models\Bankverbindung;
use backend\models\BankverbindungSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $searchModel = new BankverbindungSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
            $model = new Bankverbindung();
            if ($model->loadAll(Yii::$app->request->post())) {
                $laenderkennung = $model->laenderkennung;
                $kontonummer = $model->kontoNr;
                $blz = $model->blz;
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
                if (substr($blz, -1) == ' ') {
                    $blz = substr($bla, 0, -1);
                } else if (substr($kontonummer, -1) == ' ') {
                    $kontonummer = substr($kontonummer, 0, -1);
                }
                $iban = $this->CalcIban($laenderkennung, $blz, $kontonummer, $model, $id);
                if (!$iban) {
                    $message = 'IbanRaw hat in der gekapselten Methode CalcIban() die falsche Länge. Informieren Sie den Softwarehersteller oder überprüfen Sie Ihre Eingaben.';
                    $this->message($message, 'Error!', 250, Growl::TYPE_GROWL);
                    return $this->render('create', ['model' => $model, 'id' => $id,]);
                } else
                    return $this->redirect(['conclusion', 'id' => $id, 'laenderkennung' => $laenderkennung, 'kontonummer' => $kontonummer, 'blz' => $blz, 'institut' => $institut, 'bic' => $bic, 'iban' => $iban]);
            } else {
                return $this->render('create', ['model' => $model, 'id' => $id]);
            }
        } catch (\Exception $error) {
            error_handling::error_without_id($error, BankverbindungController::RenderBackInCaseOfError);
        }
    }

    public function actionConclusion($id, $laenderkennung, $kontonummer, $blz, $institut, $bic, $iban) {
        $model = new Bankverbindung();
        if ((Yii::$app->request->post())) {
            $model->laenderkennung = $laenderkennung;
            $model->institut = $institut;
            $model->blz = $blz;
            $model->kontoNr = $kontonummer;
            $model->iban = $iban;
            $model->bic = $bic;
            $model->save();
            $this->redirect(['/bankverbindung/index']);
            /*
              ToDO:Save record into database
             */
        } else {
            return $this->render('_form_conclusion', [
                        'id' => $id,
                        'laenderkennung' => $laenderkennung,
                        'kontonummer' => $kontonummer,
                        'blz' => $blz,
                        'institut' => $institut,
                        'bic' => $bic,
                        'iban' => $iban
            ]);
        }
    }

    public function actionUpdate($id) {
        if (Yii::$app->request->post('_asnew') == '1') {
            $model = new Bankverbindung();
        } else {
            $model = $this->findModel($id);
        }

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    public function actionDelete($id) {
        $this->findModel($id)->deleteWithRelated();
        return $this->redirect(['index']);
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

    public function actionSelect() {
        $modelKunde = \frontend\models\Kunde::find()->all();
        if (!empty($modelKunde)) {
            $this->layout = 'main_immo';
            $DynamicModel = new DynamicModel(['kunde']);
            $DynamicModel->addRule(['kunde'], 'integer');
            $DynamicModel->addRule(['kunde'], 'required');

            if ($DynamicModel->load(Yii::$app->request->post())) {
                $this->redirect(['/bankverbindung/create', 'id' => $DynamicModel->kunde]);
            } else {
                return $this->render('_form_select', ['DynamicModel' => $DynamicModel]);
            }
        } else {
            $string = 'Es exisitert noch kein Kunde in der Datenbank. Steigern Sie Ihre Kundenaqkuise!';
            $this->message($message);
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
                if ($laenderkennungTranslate) {
                    $ibanRaw = $bankleitzahl . $kontonummer . $laenderkennungTranslate;
                    if (strlen($ibanRaw) == 24) {
                        $pruefsumme = 98 - bcmod($ibanRaw, 97);
                        if ($pruefsumme < 10)
                            $pruefnummer = '0' . $pruefsumme;
                        $iban = $laenderkennung . $pruefsumme . $bankleitzahl . $kontonummer;
                        return $iban;
                    } else
                        return false;
                }
            } else {
                print_r('Error!Error!Error<br>Länderkennung hat das falsche Format');
                die();
            }
        } catch (\Exception $error) {
            error_handling::error_without_id($error, BankverbindungController::RenderBackInCaseOfError);
        }
    }

}
