<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use common\models\User;
use kartik\growl\Growl;
use yii\web\Session;
use backend\models\Mail;
use app\models\MailSearch;
use frontend\models\Dateianhang;
use frontend\models\EDateianhang;

class MailController extends Controller {

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
        $searchModel = new MailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $model = $this->findModel($id);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionCreate() {
        $model = new Mail();
        $modelDateianhang = new Dateianhang(['scenario' => 'create_Dateianhang']);
        $modelEdateiAnhang = new EDateianhang();
        $mailFrom = User::findOne(Yii::$app->user->identity->id)->email;
        $Zieladresse = "";
        $Ccadresse = "";
        $Bccadresse = "";
        if ($model->loadAll(Yii::$app->request->post()) && $modelDateianhang->loadAll(Yii::$app->request->post())) {
            /* Anfang der Mailadressenvalidierung */

//Bilde ein Array aus dem Formularfeld der Mailadressen(an)
            $string2Extract = $model->mail_to;
            $extractOuter = explode("'", $string2Extract);
            $extractInner = explode(";", $extractOuter[0]);
//Bilde ein Array aus dem Formularfeld der Mailadressen(cc)
            $string2Extract = $model->mail_cc;
            $extractOuter = explode("'", $string2Extract);
            $extractInnerCc = explode(";", $extractOuter[0]);
//Bilde ein Array aus dem Formularfeld der Mailadressen(bcc)
            $string2Extract = $model->mail_bcc;
            $extractOuter = explode("'", $string2Extract);
            $extractInnerBcc = explode(";", $extractOuter[0]);
            $Ursprung = "Zieladresse";

//Validiere alle Mailadressen
            /* Validiert die Zieladressen.Eine Kapselung der Logik funktioniert leider nicht,da gekapselte Methoden nicht mehr zurück rendern können */
            for ($i = 0; $i < count($extractInner); $i++) {
                if (!filter_var($extractInner[$i], FILTER_VALIDATE_EMAIL)) {
                    $message = 'Eine oder mehrere der Mailadressen im Feld ' . $Ursprung . ' ist korrupt. Bitte überprüfen Sie deren Validität und reselektieren Sie ggf. Ihre Dateianhänge';
                    $this->Ausgabe($message);
                    /* Dieses Codestück verhindert eine Kapselung der Logik,die demzufolge das erste mal codiert werden muss */
                    return $this->render('create', [
                                'model' => $model,
                                'modelDateianhang' => $modelDateianhang,
                                'mailFrom' => $mailFrom
                    ]);
                }
            }
            if (!empty($model->mail_cc)) {
                $Ursprung = "cc";
                for ($i = 0; $i < count($extractInnerCc); $i++) {
                    if (!filter_var($extractInnerCc[$i], FILTER_VALIDATE_EMAIL)) {
                        $message = 'Eine oder mehrere der Mailadressen im Feld ' . $Ursprung . ' ist korrupt. Bitte überprüfen Sie deren Validität und reselektieren Sie ggf. Ihre Dateianhänge';
                        $this->Ausgabe($message);
                        /* Dieses Codestück verhindert eine Kapselung der Logik,die demzufolge bisher zweimal codiert werden musste */
                        return $this->render('create', [
                                    'model' => $model,
                                    'modelDateianhang' => $modelDateianhang,
                                    'mailFrom' => $mailFrom
                        ]);
                    }
                }
            }
            if (!empty($model->mail_bcc)) {
                $Ursprung = "bcc";
                for ($i = 0; $i < count($extractInnerBcc); $i++) {
                    if (!filter_var($extractInnerBcc[$i], FILTER_VALIDATE_EMAIL)) {
                        $message = 'Eine oder mehrere der Mailadressen im Feld ' . $Ursprung . ' ist korrupt. Bitte überprüfen Sie deren Validität und reselektieren Sie ggf. Ihre Dateianhänge';
                        $this->Ausgabe($message);
                        /* Dieses Codestück verhindert eine Kapselung der Logik,die demzufolge bisher dreimal codiert werden musste */
                        return $this->render('create', [
                                    'model' => $model,
                                    'modelDateianhang' => $modelDateianhang,
                                    'mailFrom' => $mailFrom
                        ]);
                    }
                }
            }
            /* Ende der Mailadressenvalidierung
              Anfang der Modellvalidierung
             */

            $Valid = $this->ValidateModels($model, $modelDateianhang);
            if (is_array($Valid)) {
                $ausgabe1 = print_r('ERROR in der Klasse ' . get_class() . '<br>');
                $ausgabe2 = var_dump($Valid);
                $ausgabe3 = 'Die Tabellen mail oder dateianhang  konnten nicht validiert werden. Informieren Sie den Softwarehersteller!';
                $ausgabeGesamt = $ausgabe1 . '<br>' . $ausgabe2 . '<br>' . $ausgabe3;
                var_dump($ausgabeGesamt);
                print_r('<br>');
                echo Html::a('back', ['/mail/index'], ['title' => 'zurück']);
                die();
            }
            /*Ende der Modellvalidierung*/
            //Verarbeite Uploaddaten
            $model->attachement = UploadedFile::getInstances($model, 'attachement');
            $anhangszaehler = 0;
 
            $model->save();
            return $this->redirect(['view',]);
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'modelDateianhang' => $modelDateianhang,
                        'mailFrom' => $mailFrom
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->loadAll(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['view', 'id' => $id]);
        } else {
            $mailFrom = User::findOne(Yii::$app->user->identity->id)->email;
            return $this->render('create', [
                        'model' => $model,
                        'mailFrom' => $mailFrom
            ]);
        }
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();

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
        if (($model = Mail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', "Das Model Mail mit der Id:$id konnte nicht geladen werden. Informieren Sie den Softwarehersteller"));
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

    private function ValidateModels($model, $modelDateianhang) {
        $x = 1;
        $Valid = $model->validate() && $modelDateianhang->validate();
        $ausgabe = array();
        if (!$Valid) {
            $ausgabe[0] = "betrifft Model Mail:";
            foreach ($model->getErrors() as $values) {
                foreach ($values as $value1) {
                    $ausgabe[$x] = $value1;
                    $x++;
                }
            }
            foreach ($modelDateianhang->getErrors()as $values) {
                $ausgabe[$x] = 'betrifft Model Dateianhang:';
                foreach ($values as $value3) {
                    $ausgabe[$x] = $value3;
                    $x++;
                }
            }
            return $ausgabe;
        } else {
            return true;
        }
    }

}
