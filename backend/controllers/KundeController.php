<?php

namespace backend\controllers;

use Yii;
use frontend\models\Kunde;
use backend\models\KundeSearch;
use frontend\models\LPlz;
use backend\models\Bankverbindung;
use common\classes\error_handling;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;
use yii\web\Session;
use kartik\growl\Growl;

class KundeController extends Controller {

    const RenderBackInCaseOfError = '/kunde/index';

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
        $countKunde = Kunde::find()->count('id');
        if ($countKunde == 0) {
            $session = new Session();
            $session->addFlash('info', 'Es exisitert noch kein Kunde in der Datenbank. Steigern Sie Ihre Kundenaqkuise!');
            return $this->redirect(['/site/index']);
        }
        $searchModel = new KundeSearch();
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

    public function actionUpdate($id) {
        $model = new Bankverbindung();
        $model = $this->findModel($id);
        $plzId = $model->l_plz_id;
        $bankId = $model->bankverbindung_id;
        //Plzstring im Formular ausgeben
        if (!empty(LPlz::findOne(['id' => $plzId])))
            $plz = LPlz::findOne(['id' => $plzId])->plz;
        else
            $plz = "00000";
        if ($model->loadAll(Yii::$app->request->post())) {
            //den Plzstring in die Id zurück verwandeln
            $plzID = LPlz::findOne(['plz' => $model->l_plz_id])->id;
            $model->l_plz_id = $plzID;
            if (!empty(Bankverbindung::findOne(['id' => $model->bankverbindung_id]))) {
                $bankID = Bankverbindung::findOne(['id' => $model->bankverbindung_id])->id;
                $model->bankverbindung_id = $bankID;
            }
            /* ToDo: prüfen, ob bankverbindung_id versehentlich über die Select2Box(_form.php) doppelt gesetzt wurde. Falls ja, darf der Record
              nicht gespeichert werden, da zwei Kunden nicht ein-und diesselbe Bankdaten haben können. Die Benachrichtigung soll über eine
              kartikBox erfolgen, gefolgt von einem render auf actionUpdate */
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'plz' => $plz,
                        'id' => $bankId
            ]);
        }
    }

    public function actionDelete($id) {
        try {
            $session = new Session();
            $this->findModel($id)->delete();
            $session->addFlash('info', "Der Kunde mit der Id:$id wurde aus der Datenbank entfernt!");
            return $this->redirect(['/site/index']);
        } catch (IntegrityException $er) {
            $message = "Der Kunde mit der Id:$id kann nicht gelöscht werden, da das gegen die referentielle Integrität verstößt. Löschen Sie zunächst die korrespondierenden Bankdaten und/oder Besichtigungstermine!";
            $this->message($message, 'Error', 1500, Growl::TYPE_DANGER);
            $searchModel = new KundeSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }
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

    public function actionSend() {
        /* Die Methode redirect() akzeptiert weder Arrays noch Objekte als Übergabeparameter. Demzufolge werden diese Parameter einer Session
          übergeben, die dann vom jeweiligen Controller aufgerufen wird */
        $session = new Session();
        $Mailadresses = array();
        $Name = array();
        $Geschlecht = array();
        /* Indizie für Adressen */
        $x = 0;
        /* Indizie für den Fremdschlüssel */
        $y = 0;
        /* Indizie für Geschlecht */
        $z = 0;
        /* Indizie für Namen */
        $zz = 0;
        try {
            $checkbox = (array) Yii::$app->request->post('selection');
            if (empty(($checkbox)) && (isset($_POST['button_checkBoxes']))) {
                $session->addFlash("warning", "Selektieren Sie die Bewerber, für die Mails erstellt werden sollen, über die Checkboxen");
                return $this->redirect(['/kunde/index']);
            } /* checkBox enthält die Id */
            foreach ($checkbox as $item) {
                $IdAnrede = Kunde::findOne(['id' => $item])->geschlecht0->typus;
                $VorName = Kunde::findOne(['id' => $item])->vorname;
                $NachName = Kunde::findOne(['id' => $item])->nachname;
                $name = $VorName . " " . $NachName;
//packe die gefundenen Values in oben initialisierte Arrays
                $Geschlecht[$z] = $IdAnrede;
                $Name[$zz] = $name;
                if (empty(Kunde::findOne(['id' => $item])->email)) {
                    $session->addFlash("warning", "Für diesen Kunden exisitert im system keine Mailadresse. Legen Sie welche an!");
                    return $this->redirect(['/kunde/index']);
                } else {
                    $Mailadresses[$x] = Kunde::findOne(['id' => $item])->email;
                }
//packe die Adressen in ein Array
                $y++;
                $z++;
                $zz++;
                $x++;
            }
//übergebe die Arrays an eine Session
            $sessionPHP = Yii::$app->session;
            if (!$sessionPHP->isActive)
                $sessionPHP->open();
            $sessionPHP['adressen'] = $Mailadresses;
            $sessionPHP['name'] = $Name;
            $sessionPHP['geschlecht'] = $Geschlecht;
            if ($sessionPHP->isActive)
                $sessionPHP->close();
            if (count($checkbox) == 1)
//render das StapelOneFormular. Dazu muss jeweils das erste Element der Arrays übergeben werden
                return $this->redirect(['/mail/stapelone', 'Mailadress' => $Mailadresses[0], 'geschlecht' => $Geschlecht[0], 'name' => $Name[0]]);
            else
//render das StapelSeveralFormular
                return $this->redirect(['/mail/stapelseveral']);
        } catch (\Exception $error) {
            error_handling::error_without_id($error, KundeController::RenderBackInCaseOfError);
        }
    }

    protected function findModel($id) {
        if (($model = Kunde::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
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
