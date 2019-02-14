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

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $model = $this->findModel($id);
        $providerKunde = new \yii\data\ArrayDataProvider([
            'allModels' => $model->kundes,
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'providerKunde' => $providerKunde,
        ]);
    }

    public function actionCreate($id) {
        try {
            $key = '_cs2YlLeMclnA504wLigtHuB9WvmKQI58EKtSVTm_mo3kULxLxIfryqWmzS9QqCJ';
            $key = 'TopSecret'; //muss durch eine kostenlose Registrierung initialisert worden sein
            $laenderkennung = 'DE';
            $kontonummer = '1911869221';
            $blz = '25050180';
            // erzeuge einen neuen cURL-Handle
            $curl = curl_init();
            curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'https://fintechtoolbox.com/bankcodes/' . $blz));
            $webserviceValues = curl_exec($curl);
            $respobj = json_decode($webserviceValues);
            $bank = $respobj->bank_code->description . ' ' . $respobj->bank_code->city;
            $bic = $respobj->bank_code->bic;
            /*var_dump($bank);
            var_dump($bic);
            curl_close($curl);
            $iban = $this->CalcIban($laenderkennung, $blz, $kontonummer);
            var_dump($iban);
            die();*/

            $model = new Bankverbindung();
            if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                            'model' => $model,
                            'id' => $id,
                ]);
            }
        } catch (\Exception $error) {
            error_handling::error_without_id($error, BankverbindungController::RenderBackInCaseOfError);
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
            return $this->render('update', [
                        'model' => $model,
            ]);
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

        $content = $this->renderAjax('_pdf', [
            'model' => $model,
            'providerKunde' => $providerKunde,
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

    public function actionSaveAsNew($id) {
        $model = new Bankverbindung();

        if (Yii::$app->request->post('_asnew') != '1') {
            $model = $this->findModel($id);
        }

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('saveAsNew', [
                        'model' => $model,
            ]);
        }
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
                return $this->render('_form_select', [
                            'DynamicModel' => $DynamicModel,
                ]);
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
                    }
                }
                return $iban;
            } else {
                print_r('Error!Error!Error<br>Länderkennung hat das falsche Format');
                die();
            }
        } catch (\Exception $error) {
            error_handling::error_without_id($error, BankverbindungController::RenderBackInCaseOfError);
        }
    }

}
