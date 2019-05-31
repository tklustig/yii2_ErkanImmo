<?php

namespace backend\controllers;

use Yii;
use backend\models\LTextbaustein;
use backend\models\LTextbausteinSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class TextbausteinController extends Controller {

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
        $searchModel = new LTextbausteinSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $model = $this->findModel($id);
        $providerMail = new \yii\data\ArrayDataProvider([
            'allModels' => $model->mails,
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'providerMail' => $providerMail,
        ]);
    }

    public function actionCreate() {
        $model = new LTextbaustein();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id) {
        if (Yii::$app->request->post('_asnew') == '1') {
            $model = new LTextbaustein();
        } else {
            $model = $this->findModel($id);
        }

        if ($model->loadAll(Yii::$app->request->post())) {
            $model->data = html_entity_decode($model->data);
            $model->save();
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
        $providerMail = new \yii\data\ArrayDataProvider([
            'allModels' => $model->mails,
        ]);

        $content = $this->renderAjax('_pdf', [
            'model' => $model,
            'providerMail' => $providerMail,
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
        $model = new LTextbaustein();

        if (Yii::$app->request->post('_asnew') != '1') {
            $model->data = html_entity_decode($model->data);
            $model->save();
            $model = $this->findModel($id);
        }

        if ($model->loadAll(Yii::$app->request->post())) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('saveAsNew', [
                        'model' => $model,
            ]);
        }
    }

    protected function findModel($id) {
        if (($model = LTextbaustein::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Die Tabelle lTextbaustein konnte nicht geladen werden. Informieren Sie den Softwarehersteller! (Fehlercode:XtZ22570)'));
        }
    }

    public function actionAddMail() {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Mail');
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formMail', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
