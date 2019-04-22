<?php

namespace backend\controllers;

use Yii;
use backend\models\Kopf;
use backend\models\KopfSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\alert\Alert;
use kartik\widgets\Growl;

class KopfController extends Controller {

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
        $searchModel = new KopfSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $model = $this->findModel($id);
        $providerRechnung = new \yii\data\ArrayDataProvider([
            'allModels' => $model->rechnungs,
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'providerRechnung' => $providerRechnung,
        ]);
    }

    public function actionCreate() {
        $model = new Kopf();
        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id) {
        $message = "Bitte ändern Sie auf keinen Fall die Asterikse(****,++++,::::)<br>Sie werden alle zum Ersetzen durch Ihre Firmenbegriffe benötigt!";
        $this->Ausgabe($message, 'Wichtige Info', 1500, Growl::TYPE_WARNING);
        if (Yii::$app->request->post('_asnew') == '1') {
            $model = new Kopf();
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
        $this->findModel($id)->delete();
        $message = "Der Rechnungskopf mit der Id:$id wurde aus Ihrer Datenbank entfernt";
        echo Alert::widget([
            'type' => Alert::TYPE_INFO,
            'title' => 'Importan Message',
            'icon' => 'fas fa-info-circle',
            'body' => $message,
            'showSeparator' => true,
            'delay' => false
        ]);
        $searchModel = new KopfSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPdf($id) {
        $model = $this->findModel($id);
        $providerRechnung = new \yii\data\ArrayDataProvider([
            'allModels' => $model->rechnungs,
        ]);

        $content = $this->renderAjax('_pdf', [
            'model' => $model,
            'providerRechnung' => $providerRechnung,
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
        $model = new Kopf();

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
        if (($model = Kopf::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionAddRechnung() {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Rechnung');
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formRechnung', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionBaustein($textId) {
        $text = Kopf::findOne($textId)->data;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $text;
    }

    protected function Ausgabe($message, $typus = 'Warnung', $delay = 1000, $type = Growl::TYPE_GROWL) {
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
