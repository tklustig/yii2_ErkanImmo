<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Immobilien;
use frontend\models\ImmobilienSearch;
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
     * Lists all Immobilien models.
     * @return mixed
     */
    public function actionIndex() {
        $this->layout = 'main_immo';
        $searchModel = new ImmobilienSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('_index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
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

    /**
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
    protected function findModel($id) {
        if (($model = Immobilien::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for Besichtigungstermin
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddBesichtigungstermin() {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Besichtigungstermin');
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formBesichtigungstermin', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for EDateianhang
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddEDateianhang() {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('EDateianhang');
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formEDateianhang', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for Kundeimmobillie
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     * @return mixed
     */
    public function actionAddKundeimmobillie() {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Kundeimmobillie');
            if ((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formKundeimmobillie', ['row' => $row]);
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

}
