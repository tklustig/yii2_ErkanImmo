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
use frontend\models\Kundeimmobillie;
use frontend\models\Adminbesichtigungkunde;

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
        $modelKundeImmo = new Kundeimmobillie();
        $modelAdminBesKunde = new Adminbesichtigungkunde();
        if ($model->load(Yii::$app->request->post()) && $modelKunde->load(Yii::$app->request->post())) {
            if ($modelKunde->l_plz_id == "")
                $modelKunde->l_plz_id = null;
            //handle ForeignKey Immobilien_id in table besichtigungstermin
            $immoId = Immobilien::findOne(['id' => $id])->id;
            $model->Immobilien_id = $immoId;
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
            //Die Überprüfung der Strasse auf eine Hausnummer klappt mit dem pregmatchPattern
            $string2Array = explode(' ', $modelKunde->strasse);
            if (count($string2Array) < 1)
                $bool = false;
            else
                $bool = true;
            if ($bool) {
                $pattern = '/(\d+)[\s\-]*([a-zA-Z]*)/';
                foreach ($string2Array as $item) {
                    $result = preg_match($pattern, $item, $matches);
                    if ($result)
                        $bool = true;
                    else
                        $bool = false;
                }
            }
            if (!$bool) {
                $message = "Die Strasse enthält keine vom Namen abgesonderte Hausnummer.";
                $this->message($message, 'Error', 1250, Growl::TYPE_DANGER);
                return $this->render('create', ['model' => $model, 'modelKunde' => $modelKunde, 'id' => $id]);
            }
            $model->validate();
            if (!$model->validate()) {
                print_r("<center><h2>ModelTermine ist invalide</h2></center>");
                var_dump($model);
                $bool = true;
            }
            $modelKunde->validate();
            if (!$modelKunde->validate()) {
                print_r("<center><h2>ModelKunde ist invalide</h2></center>");

                $bool = true;
            }
            if (!$bool) {
                var_dump($modelKunde);
                var_dump($model);
                var_dump($matches);
                var_dump($bool);
                die();
            }
            //Die gesamten Schreibprozesse in die Datenbank müssen eigentlich in eine Tranaction verfrachtet werden
            $createdBy=$model->angelegt_von;
            $modelKunde->angelegt_von = $modelKunde->id;
            $modelKunde->save();
            $model->angelegt_von = $modelKunde->id;
            $model->save();
            $modelKundeImmo->kunde_id = $modelKunde->id;
            $modelKundeImmo->immobilien_id = $immoId;
            $modelKundeImmo->save();
            $modelAdminBesKunde->besichtigungstermin_id = $model->id;
            $modelAdminBesKunde->admin_id = $createdBy;
            $modelAdminBesKunde->kunde_id = $modelKunde->id;
            $modelAdminBesKunde->save();
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

    private function message($message, $typus = 'Warnung', $delay = 1000, $type = Growl::TYPE_GROWL) {
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
