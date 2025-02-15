<?php

namespace backend\controllers;

use Yii;
use backend\models\Rechnung;
use backend\models\RechnungSearch;
use backend\models\Firma;
use frontend\models\Kunde;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use kartik\growl\Growl;
use kartik\mpdf\Pdf;

class RechnungController extends Controller {

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
        $countRechnung = Rechnung::find()->count('id');
        if ($countRechnung == 0) {
            $session = Yii::$app->session;
            $session->addFlash('info', 'Es exisitieren noch keine Rechnungen in der Datenbank. Steigern Sie Ihre Kundenaqkuise oder hinterlegen Sie deren Rechnungen!');
            return $this->redirect(['/site/index']);
        }
        $searchModel = new RechnungSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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

    public function actionCreate() {
        $countKunde = Kunde::find()->count('id');
        if ($countKunde == 0) {
            $session = Yii::$app->session;
            $session->addFlash('info', 'Es exisitieren noch keine Kunden in der Datenbank, denen Sie Rechnungen schicken könnten. Steigern Sie Ihre Kundenaqkuise!');
            return $this->redirect(['/site/index']);
        }
        $model = new Rechnung();
        if ($model->loadAll(Yii::$app->request->post())) {
            $arrayContent = array();
            $arrayContent[0] = "Reklamationen";
            $arrayContent[1] = "688";
            $arrayContent[2] = "Zivilprozessordnung";
            $arrayContent[3] = "Widerspruchsregelungen";
            if (!$this->CheckIfStringContainsElement($model->rechnungPlain, $arrayContent)) {
                $message = 'Die Rechnung muss den gestzlichen Normen entsprechen. Erstellen Sie die Rechnung, indem Sie per Copy&Paste den Inhalt des Feldes Zusatz einfügen!';
                $this->Ausgabe($message);
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id) {
        if (Yii::$app->request->post('_asnew') == '1') {
            $model = new Rechnung();
        } else {
            $model = $this->findModel($id);
        }

        if ($model->loadAll(Yii::$app->request->post())) {
            $arrayContent = array();
            $arrayContent[0] = "Reklamationen";
            $arrayContent[1] = "688";
            $arrayContent[2] = "Zivilprozessordnung";
            $arrayContent[3] = "Widerspruchsregelungen";
            if (!$this->CheckIfStringContainsElement($model->rechnungPlain, $arrayContent)) {
                $message = 'Die Rechnung muss den gestzlichen Normen entsprechen. Erstellen Sie die Rechnung, indem Sie per Copy&Paste den Inhalt des Feldes Zusatz einfügen!';
                $this->Ausgabe($message);
                return $this->render('create', [
                            'model' => $model,
                ]);
            }
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

        $content = $this->renderAjax('_pdf', [
            'model' => $model,
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
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
        $model = new Rechnung();

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

    public function actionPrint($id) {
        $data = Rechnung::findOne(['id' => $id])->rechnungPlain;
        $rechnungVon = 'Rechnung/Unterlagen von Makler ' . Rechnung::findOne(['id' => $id])->makler->username . ' an ';
        $geschlecht = Rechnung::findOne(['id' => $id])->kunde->geschlecht0->typus;
        $vorname = Rechnung::findOne(['id' => $id])->kunde->vorname;
        $nachname = Rechnung::findOne(['id' => $id])->kunde->nachname;
        $maxId = Firma::find()->max('id');
        $bankDatenFirma = Firma::findOne(['id' => $maxId])->bankdaten;
        $rechnungAn = $geschlecht . ' ' . $vorname . ' ' . $nachname;
        $rechnungTitle = $rechnungVon . ' ' . $rechnungAn;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $data,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:10px}',
            'options' => ['title' => $rechnungTitle],
            'methods' => [
                'SetHeader' => $rechnungTitle,
                'SetFooter' => $bankDatenFirma,
            ]
        ]);
        return $pdf->render(array($pdf->render(), 'target' => '_blank'));
    }

    protected function findModel($id) {
        if (($model = Rechnung::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    private function CheckIfStringContainsElement($strBegriff, $arrContent) {
        foreach ($arrContent as $item) {
            if (strpos($strBegriff, $item) !== false)
                return true;
        }
        return false;
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
