<?php

namespace backend\controllers;

use Yii;
use backend\models\Immobilien;
use backend\models\ImmobilienSearch;
use yii\web\Controller;
use yii\base\DynamicModel;
use yii\web\NotFoundHttpException;
use yii\web\NotAcceptableHttpException;
use yii\filters\VerbFilter;

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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
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
        $model_Dateianhang = new \frontend\models\Dateianhang();
        $model = new Immobilien();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            $valid = $model->validate();
            $isValid = $model_Dateianhang->validate() && $valid;
            if ($isValid) {
                return $this->redirect(['view', 'id' => $model->id, 'l_plz_id' => $model->l_plz_id, 'l_stadt_id' => $model->l_stadt_id, 'user_id' => $model->user_id, 'l_art_id' => $model->l_art_id]);
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
        if (Yii::$app->request->post('_asnew') == '1') {
            $model = new Immobilien();
        } else {
            $model = $this->findModel($id);
        }

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id, 'l_plz_id' => $model->l_plz_id, 'l_stadt_id' => $model->l_stadt_id, 'user_id' => $model->user_id, 'l_art_id' => $model->l_art_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'model_Dateianhang' => $model_Dateianhang
            ]);
        }
    }

    public function actionDelete($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id) {
        $this->findModel($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id)->deleteWithRelated();

        return $this->redirect(['index']);
    }

    public function actionPdf($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id) {
        $model = $this->findModel($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id);
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

    public function actionSaveAsNew($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id) {
        $model = new Immobilien();

        if (Yii::$app->request->post('_asnew') != '1') {
            $model = $this->findModel($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id);
        }

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id, 'l_plz_id' => $model->l_plz_id, 'l_stadt_id' => $model->l_stadt_id, 'user_id' => $model->user_id, 'l_art_id' => $model->l_art_id]);
        } else {
            return $this->render('saveAsNew', [
                        'model' => $model,
            ]);
        }
    }

    protected function findModel($id) {
        if (($model = Immobilien::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
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

}
