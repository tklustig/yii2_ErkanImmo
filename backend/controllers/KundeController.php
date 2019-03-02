<?php

namespace backend\controllers;

use Yii;
use frontend\models\Kunde;
use backend\models\KundeSearch;
use backend\models\LPlz;
use backend\models\Bankverbindung;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class KundeController extends Controller {

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
        $searchModel = new KundeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Kunde model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $providerAdminbesichtigungkunde = new \yii\data\ArrayDataProvider([
            'allModels' => $model->adminbesichtigungkundes,
        ]);
        $providerEDateianhang = new \yii\data\ArrayDataProvider([
            'allModels' => $model->eDateianhangs,
        ]);
        $providerKundeimmobillie = new \yii\data\ArrayDataProvider([
            'allModels' => $model->kundeimmobillies,
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'providerAdminbesichtigungkunde' => $providerAdminbesichtigungkunde,
                    'providerEDateianhang' => $providerEDateianhang,
                    'providerKundeimmobillie' => $providerKundeimmobillie,
        ]);
    }

    public function actionUpdate($id) {
        $model = new Bankverbindung(['scenario' => 'update_kunde']);
        $model = $this->findModel($id);
        $plzId = $model->l_plz_id;
        $bankId = $model->bankverbindung_id;
        if (!empty(LPlz::findOne(['id' => $plzId])))
            $plz = LPlz::findOne(['id' => $plzId])->plz;
        else
            $plz = "00000";
        if (!empty(Bankverbindung::findOne(['id' => $bankId])))
            $bank = Bankverbindung::findOne(['id' => $bankId])->institut;
        else {
            $plz = '00000';
            $bank = '00000';
        }
        if ($model->loadAll(Yii::$app->request->post())) {
            $plzID = LPlz::findOne(['plz' => $model->l_plz_id])->id;
            $model->l_plz_id = $plzID;
            $bankID = Bankverbindung::findOne(['id' => $model->bankverbindung_id])->id;
            $model->bankverbindung_id = $bankID;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'plz' => $plz,
                        'bank' => $bank,
            ]);
        }
    }

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
        if (($model = Kunde::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

}
