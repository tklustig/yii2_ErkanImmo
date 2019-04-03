<?php

namespace backend\controllers;

use Yii;
use backend\models\LBegriffe;
use app\models\LBegriffeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\growl\Growl;

class BegriffeController extends Controller {

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
        $count = LBegriffe::find()->count('id');
        $modelBegriffe = LBegriffe::find()->all();
        $arrayForPropData = array();
        foreach ($modelBegriffe as $item) {
            array_push($arrayForPropData, $item->data);
        }
        $searchModel = new LBegriffeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($count < 10) {
            $message = "Da derzeit nur $count Records in dieser Tabelle hinterlegt sind, wird die Zuweisung im Frontend(Impressum) nicht funktionieren. Informieren Sie den Softwarehersteller!";
            $this->Ausgabe($message, 'Error', 500, Growl::TYPE_DANGER);
        }
        if (count($arrayForPropData) < 10) {
            $message = 'Damit die Zuweisung der Begriffe im Frontend(Impressum) funktionert, müssen Sie für alle Records jeweils die Spalte data mit einem Wert belegt haben! Derzeit sind aber nur ' . count($arrayForPropData) . ' Werte belegt';
            $this->Ausgabe($message, 'Info', 1500, Growl::TYPE_SUCCESS);
        }

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

    /*
      public function actionCreate()
      {
      $model = new LBegriffe();

      if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
      return $this->redirect(['view', 'id' => $model->id]);
      } else {
      return $this->render('create', [
      'model' => $model,
      ]);
      }
      }

     */

    public function actionUpdate($id) {
        if (Yii::$app->request->post('_asnew') == '1') {
            $model = new LBegriffe();
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

    /*
      public function actionDelete($id) {
      $this->findModel($id)->deleteWithRelated();

      return $this->redirect(['index']);
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
      'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
      'cssInline' => '.kv-heading-1{font-size:18px}',
      'options' => ['title' => \Yii::$app->name],
      'methods' => [
      'SetHeader' => [\Yii::$app->name],
      'SetFooter' => ['{PAGENO}'],
      ]
      ]);

      return $pdf->render();
      }

     */

    /*
      public function actionSaveAsNew($id) {
      $model = new LBegriffe();

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

     */

    protected function findModel($id) {
        if (($model = LBegriffe::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
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

}
