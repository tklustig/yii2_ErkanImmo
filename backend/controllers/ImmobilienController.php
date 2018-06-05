<?php

namespace backend\controllers;

use Yii;
use backend\models\Immobilien;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ImmobilienController implements the CRUD actions for Immobilien model.
 */
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

    /**
     * Displays a single Immobilien model.
     * @param integer $id
     * @param integer $l_plz_id
     * @param integer $l_stadt_id
     * @param integer $user_id
     * @param integer $l_art_id
     * @return mixed
     */
    public function actionView($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id) {
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
        return $this->render('view', [
                    'model' => $this->findModel($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id),
                    'providerBesichtigungstermin' => $providerBesichtigungstermin,
                    'providerEDateianhang' => $providerEDateianhang,
                    'providerKundeimmobillie' => $providerKundeimmobillie,
        ]);
    }

    /*
     * Creates a new Immobilien model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate() {
        $model = new Immobilien();
        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id, 'l_plz_id' => $model->l_plz_id, 'l_stadt_id' => $model->l_stadt_id, 'user_id' => $model->user_id, 'l_art_id' => $model->l_art_id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Immobilien model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $l_plz_id
     * @param integer $l_stadt_id
     * @param integer $user_id
     * @param integer $l_art_id
     * @return mixed
     */
    public function actionUpdate($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id) {
        if (Yii::$app->request->post('_asnew') == '1') {
            $model = new Immobilien();
        } else {
            $model = $this->findModel($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id);
        }

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id, 'l_plz_id' => $model->l_plz_id, 'l_stadt_id' => $model->l_stadt_id, 'user_id' => $model->user_id, 'l_art_id' => $model->l_art_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Immobilien model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $l_plz_id
     * @param integer $l_stadt_id
     * @param integer $user_id
     * @param integer $l_art_id
     * @return mixed
     */
    public function actionDelete($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id) {
        $this->findModel($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id)->deleteWithRelated();
        return $this->redirect(['index']);
    }

    /**
     *
     * Export Immobilien information into PDF format.
     * @param integer $id
     * @param integer $l_plz_id
     * @param integer $l_stadt_id
     * @param integer $user_id
     * @param integer $l_art_id
     * @return mixed
     */
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

    /**
     * Creates a new Immobilien model by another data,
     * so user don't need to input all field from scratch.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @param mixed $id
     * @return mixed
     */
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

    /**
     * Finds the Immobilien model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $l_plz_id
     * @param integer $l_stadt_id
     * @param integer $user_id
     * @param integer $l_art_id
     * @return Immobilien the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $l_plz_id, $l_stadt_id, $user_id, $l_art_id) {
        if (($model = Immobilien::findOne(['id' => $id, 'l_plz_id' => $l_plz_id, 'l_stadt_id' => $l_stadt_id, 'user_id' => $user_id, 'l_art_id' => $l_art_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
