<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Besichtigungstermin;
use frontend\models\Kunde;
use frontend\models\TerminSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Immobilien;
use frontend\models\LPlz;
use yii\db\Query;
use kartik\growl\Growl;
use common\models\User;

class TerminController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $searchModel = new TerminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $this->layout = "main_immo";
        return $this->render('view', ['model' => $this->findModel($id), 'id' => $id]);
    }

    public function actionCreate($id) {
        $this->layout = "main_immo";
        $model = new Besichtigungstermin();
        $modelKunde = new Kunde();
        if ($model->load(Yii::$app->request->post()) && $modelKunde->load(Yii::$app->request->post())) {
            if ($modelKunde->l_plz_id == "")
                $modelKunde->l_plz_id = null;
            //$year = preg_replace('/^[^\d]*(\d{4}).*$/', '\1', $model->uhrzeit);
            preg_match("/(\d{4})-\d{2}-\d{2} +(\d{2}):\d{2}:\d{2}/", $model->uhrzeit, $matches);
            $wholeString = $matches[0];
            $year = $matches[1];
            $hour = $matches[2];
            if ($hour < 6 || $hour > 19) {
                $maklerId = $model->angelegt_von;
                $makler = User::findOne(['id' => $maklerId])->username;
                $message = "Uhrzeit ist außerhalb der Arbeitszeiten unserer Makler's Herr/Frau $makler.";
                $this->message($message);
                return $this->render('create', ['model' => $model, 'modelKunde' => $modelKunde, 'id' => $id]);
            }
            $model->validate();
            if (!$model->validate()) {
                print_r("<br>ModelTermine ist invalide<br>");
                var_dump($model);
            }
            $modelKunde->validate();
            if (!$modelKunde->validate()) {
                print_r("<br>ModelKunde ist invalide<br>");
                var_dump($modelKunde);
                die();
            }
            $immoId = Immobilien::findOne(['id' => $id])->id;
            $model->Immobilien_id = $immoId;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model, 'modelKunde' => $modelKunde, 'id' => $id]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', ['model' => $model,]);
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id) {
        if (($model = Besichtigungstermin::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
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

    public function actionAuswahl($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('id, plz AS text')
                    ->from('l_plz')
                    ->where(['like', 'plz', $q]);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => LPlz::find($id)->plz];
        }
        return $out;
    }

    private function message($message) {
        echo Growl::widget([
            'type' => Growl::TYPE_GROWL,
            'title' => 'Warning!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => $message,
            'showSeparator' => true,
            'delay' => 1000,
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
