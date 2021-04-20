<?php

/*
  Gemäß dem MCV-Prinzip für die Logik der Applikation zuständig
  Klasse erstellt durch Gii und modifizieert durch den Entwickler(s.u.)
  Methoden: ©by Thomas Kipp, Debberoder Sztr.61, 30559 Hannover, http://tklustig.de, kipp.thomas@tklustig.de
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\ContactForm;
use kartik\widgets\Alert;
use backend\models\LBegriffe;

class SiteController extends Controller {

    const OPS = "WINNT";

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /* Regelt die Logik der Startseite */

    public function actionIndex() {
        $this->layout = "main_kanat";
        return $this->render('index', [
        ]);
    }

    /* Regelt die Logik der Logoutseite */

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /* Regelt die Logik der Kontaktseite */

    public function actionContact() {
        $this->layout = "main_kontakt";
        $modelBegriffe = LBegriffe::find()->all();
        $arrayOfBegriffe = array();
        foreach ($modelBegriffe as $item) {
            array_push($arrayOfBegriffe, $item->data);
        }
        $zaehler = 0;
        for ($i = 0; $i < count($arrayOfBegriffe); $i++) {
            if ($arrayOfBegriffe[$i] != "")
                $zaehler += 1;
        }
        if ($zaehler < 10) {
            Yii::$app->session->setFlash('error', 'Es exisitieren keine oder zu wenige Firmenbegriffe in der Datenbank. Erst, wenn der Admin alle erforderlichen Firmenbegriffe eingepflegt hat, lässt sich dieses Feature aufrufen.');
            return $this->redirect(['/site/index']);
        }
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post())) {
            if (PHP_OS !== SiteController::OPS) {
                $model->email;
                if ($model->sendEmail($model->email))
                    Yii::$app->session->setFlash('info', "Ihre Nachricht wurde weitergeleitet. Wir werden Sie schnellstmöglichst unter Ihrer Mailadresse $model->email kontaktieren!<br> Mit freundlichen Grüßen   Kanat Immobilien");
                else
                    Yii::$app->session->setFlash('error', '(Ihre Nachricht konnte nicht weitergeleitet werden. Versuchen Sie es erneut!');
            }
            $this->redirect(["/site/index"]);
        } else {
            return $this->render('contact', [
                        'model' => $model,
                        'arrayOfBegriffe' => $arrayOfBegriffe
            ]);
        }
    }

    /* Regelt die Logik der Impressumseite */

    public function actionAbout() {
        $this->layout = "main_kontakt";
        $modelBegriffe = LBegriffe::find()->all();
        $arrayOfBegriffe = array();
        foreach ($modelBegriffe as $item) {
            array_push($arrayOfBegriffe, $item->data);
        }
        $zaehler = 0;
        for ($i = 0; $i < count($arrayOfBegriffe); $i++) {
            if ($arrayOfBegriffe[$i] != "")
                $zaehler += 1;
        }
        if ($zaehler < 10) {
            Yii::$app->session->setFlash('error', 'Es exisitieren keine oder zu wenige Impressumbegriffe in der Datenbank. Erst, wenn der Admin alle 10 Begriffe eingepflegt hat, lässt sich dieses Feature aufrufen.');
            return $this->redirect(['/site/index']);
        }
        return $this->render('about', [
                    'arrayOfBegriffe' => $arrayOfBegriffe
        ]);
    }

}
