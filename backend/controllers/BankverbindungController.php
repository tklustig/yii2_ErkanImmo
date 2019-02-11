<?php

namespace backend\controllers;

use Yii;
use backend\models\Bankverbindung;
use backend\models\BankverbindungSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BankverbindungController implements the CRUD actions for Bankverbindung model.
 */
class BankverbindungController extends Controller
{
    public function behaviors()
    {
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
     * Lists all Bankverbindung models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BankverbindungSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bankverbindung model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $providerKunde = new \yii\data\ArrayDataProvider([
            'allModels' => $model->kundes,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerKunde' => $providerKunde,
        ]);
    }

    /**
     * Creates a new Bankverbindung model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bankverbindung();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Bankverbindung model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->post('_asnew') == '1') {
            $model = new Bankverbindung();
        }else{
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

    /**
     * Deletes an existing Bankverbindung model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->deleteWithRelated();
        return $this->redirect(['index']);
    }
    
    /**
     * 
     * Export Bankverbindung information into PDF format.
     * @param integer $id
     * @return mixed
     */
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

    /**
    * Creates a new Bankverbindung model by another data,
    * so user don't need to input all field from scratch.
    * If creation is successful, the browser will be redirected to the 'view' page.
    *
    * @param mixed $id
    * @return mixed
    */
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
    
    /**
     * Finds the Bankverbindung model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bankverbindung the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bankverbindung::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for Kunde
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return mixed
    */
    public function actionAddKunde()
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Kunde');
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formKunde', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
