<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Besichtigungstermin;
use frontend\models\Kunde;
use frontend\models\TerminSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;
use yii\db\Query;
use yii\web\Session;
use yii\helpers\Url;
use kartik\growl\Growl;
//eigene Klassen
use frontend\models\Immobilien;
use frontend\models\LPlz;
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

    public function actionIndex($id = NULL) {
        //keine Termine vorhanden?
        $countTermine = Besichtigungstermin::find()->count('id');
        if ($countTermine == 0) {
            $message = 'Es exisitieren noch keine Besichtigungstermine  in der Datenbank. Steigern Sie Ihre Kundenaqkuise!';
            $link = \Yii::$app->urlManagerBackend->baseUrl . '/home';
            $zusatz = '?message=Es+exisitieren+noch+keine+Besichtigungstermine++in+der+Datenbank.+Steigern+Sie+Ihre+Kundenaqkuise%21';
            return $this->redirect($link . $zusatz);
        }
        $searchModel = new TerminSearch();
        /*  sofern eine Maklerid übergeben wurde, zeige nur diejenigen Records an, deren PK als FK(besichtigungstermin_id) 
          in adminbesichtigungkunde vorhanden ist */
        $arrayOfFk = array();
        if ($id != null) {
            $modeladminBesKu = Adminbesichtigungkunde::find()->all();
            foreach ($modeladminBesKu as $item) {
                if ($item->admin_id == $id)
                    array_push($arrayOfFk, $item->besichtigungstermin_id);
            }
            /*  sofern das Array nicht leer, lege eine Session an, da nur so der Wert an die Methode actionLink(), die in der index über eine 
              Anonymous Function aufgerufen wird, übergeben werden kann
             */
            if (!empty($arrayOfFk)) {
                $searchModel->foreignKeys = $arrayOfFk;
                $sessionPHP = Yii::$app->session;
                $sessionPHP->open();
                $sessionPHP['foreignKeys'] = $arrayOfFk;
                $sessionPHP->close();
            }
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //wurden keine Besichtigungstermine gefunden, aber danach gesucht?
        if ($id != null && empty($arrayOfFk)) {
            $message = 'Für diesen Makler wurde noch kein Besichtigungstermin festgelegt. Wir raten zur umgehender Kündigung dieser faulen Ratte.';
            $this->message($message, 'Warnung!', 2000, Growl::TYPE_WARNING);
            return $this->redirect(['/termin/preselect', 'message' => $message]);
            //wurden Besichtigungstermine gefunden, dann erstelle den Header für den entsprechenden Makler(intern:User)
        } else if ($id != null && !empty($arrayOfFk)) {
            $makler = User::findOne(['id' => $id])->username;
            $header = "Besichtigungstermine für Makler $makler anzeigen";
            return $this->render('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'header' => $header
            ]);
            //nix traf zu? Dann render die index ohne Einschränkungen
        } else
            return $this->render('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
    }

    public function actionPreselect($message = NULL) {
        if ($message != NULL)
            $this->message($message, 'Warnung!', 2000, Growl::TYPE_WARNING);
        $DynamicModel = new DynamicModel(['id_user']);
        $DynamicModel->addRule(['id_user'], 'integer');
        $DynamicModel->addRule(['id_user'], 'required');
        if ($DynamicModel->load(Yii::$app->request->post())) {
            return $this->redirect(['/termin/index', 'id' => $DynamicModel->id_user]);
        } else {
            return $this->render('_form_terminpreselect', [
                        'DynamicModel' => $DynamicModel
            ]);
        }
    }

    public function actionView($id) {
        $this->layout = "main_immo";
        $kundenId = Adminbesichtigungkunde::findOne(['besichtigungstermin_id' => $id])->kunde_id;
        $wohnortKunde = Kunde::findOne(['id' => $kundenId])->stadt;
        $immoId = Kundeimmobillie::findOne(['kunde_id' => $kundenId])->immobilien_id;
        $immoPlace = Immobilien::findOne(['id' => $immoId])->stadt;
        return $this->render('view', ['model' => $this->findModel($id), 'id' => $id, 'wohnortKunde' => $wohnortKunde, 'immoPlace' => $immoPlace]);
    }

    public function actionCreate($id) {
        $arrayOfErrors = array();
        $this->layout = "main_immo";
        $model = new Besichtigungstermin();
        $modelKunde = new Kunde();
        $modelKundeImmo = new Kundeimmobillie();
        $modelAdminBesKunde = new Adminbesichtigungkunde();
        if ($model->load(Yii::$app->request->post()) && $modelKunde->load(Yii::$app->request->post())) {
            if ($modelKunde->l_plz_id == "")
                $modelKunde->l_plz_id = null;
            if (empty($modelKunde->telefon) && empty($modelKunde->email)) {
                $message = "Bitte geben Sie entweder eine Telefonnumer oder eine Mailadresse an!";
                $this->message($message, 'Warnung', 1250, Growl::TYPE_WARNING);
                return $this->render('create', ['model' => $model, 'modelKunde' => $modelKunde, 'id' => $id]);
            }
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
                $arrayOfErrors[0] = '<center><h2>ModelBesichtigungstermin ist invalide</h2></center>';
                $bool = false;
            }
            $modelKunde->validate();
            if (!$modelKunde->validate()) {
                if (count($arrayOfErrors) == 1)
                    $arrayOfErrors[1] = '<center><h2>ModelKunde ist invalide</h2></center>';
                else
                    $arrayOfErrors[0] = '<center><h2>ModelKunde ist invalide</h2></center>';
                $bool = false;
            }
            if (!$bool) {
                print_r('<br><br>');
                print_r('Fehler während der Validierung der Datenbankklassen. Bitte informieren Sie den Softwarehersteller.');
                for ($i = 0; $i < count($arrayOfErrors); $i++) {
                    print_r($arrayOfErrors[$i]);
                }
                var_dump('Modelerrors:' . $model->getErrors());
                var_dump('Matches' . $matches);
                die();
            }
            //ToDo: Die gesamten Schreibprozesse bzgl. der Datenbank müssen eigentlich in eine Tranaction verfrachtet werden
            $maklerSollBearbeiten = $model->angelegt_von;
            $modelKunde->angelegt_von = $maklerSollBearbeiten;
            $modelKunde->save();
            $model->angelegt_von = $modelKunde->id;
            $model->save();
            $modelKundeImmo->kunde_id = $modelKunde->id;
            $modelKundeImmo->immobilien_id = $immoId;
            $modelKundeImmo->save();
            $modelAdminBesKunde->besichtigungstermin_id = $model->id;
            $modelAdminBesKunde->admin_id = $maklerSollBearbeiten;
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
        $idOfAdminBesKu = Adminbesichtigungkunde::findOne(['besichtigungstermin_id' => $id])->id;
        // die RI gewährleistet, dass keine Datenbankanomalien entstehen.
        $this->findModelAdminBesKunde($idOfAdminBesKu)->delete();
        $this->findModel($id)->delete();
        $message = 'Es exisitieren noch keine Besichtigungstermine  in der Datenbank. Steigern Sie Ihre Kundenaqkuise!';
        $link = \Yii::$app->urlManagerBackend->baseUrl . '/home';
        $zusatz = '?message=Der+Termin+wurde+mitsamt+seinen++Relationen+gelöscht%21';
        return $this->redirect($link . $zusatz);
    }

    protected function findModel($id) {
        if (($model = Besichtigungstermin::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findModelAdminBesKunde($id) {
        if (($model = Adminbesichtigungkunde::findOne($id)) !== null) {
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

    public function actionLink($id, $header = null) {
        $searchModel = new TerminSearch();
        $sessionPHP = Yii::$app->session;
        $sessionPHP->open();
        if (!empty($sessionPHP['foreignKeys'])) {
            $searchModel->foreignKeys = $sessionPHP['foreignKeys'];
        }
        $sessionPHP->close();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($header != null)
            return $this->render('showLink', [
                        'id' => $id,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'header' => $header
            ]);
        else
            return $this->render('showLink', [
                        'id' => $id,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider
            ]);
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
