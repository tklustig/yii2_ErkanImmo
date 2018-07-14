<?php

/*
  Gemäß dem MCV-Prinzip für die Logik der Applikation zuständig
  Klasse erstellt durch Gii
  Methoden: ©by Thomas Kipp, Klein - Buchholzer - Kirchweg 25, 30659 Hannover, http://tklustig.ddns.net:1025, tklustig.thomas@gmail.com
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Session;
use frontend\models\ContactForm;
use kartik\widgets\Alert;

class SiteController extends Controller {

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

    public function actionIndex($id = NULL) {
        if ($id == 1) {
?><?=

            Alert::widget([
                'type' => Alert::TYPE_DANGER,
                'title' => 'Konfigurationsfehler',
                'icon' => 'glyphicon glyphicon-remove-sign',
                'body' => 'Unter NGINX lässt sich das Backend auf meinem Pi nicht ansteuern. Sobald die Applikation auf Strato gehostet wird, können Sie sich in das Backend einloggen, vorausgesetzt, Sie haben das Passwort...',
                'showSeparator' => true,
                'delay' => false
            ]);
        }
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
        $session = new Session();
        $this->layout = "main_kontakt";
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post())) {
?><?=

            Alert::widget([
                'type' => Alert::TYPE_DANGER,
                'title' => 'Konfigurationsfehler',
                'icon' => 'glyphicon glyphicon-remove-sign',
                'body' => 'Auf meinem Pi läuft PHP 5.6! Damit der Mailversand funktioniert, muss allerdings PHP 7.X installiert sein. Sobald die Applikation auf Strato gehostet wird, dürfte der Mailversand keine Probleme mehr bereiten...',
                'showSeparator' => true,
                'delay' => false
            ]);
            return $this->render('contact', [
                        'model' => $model,
            ]);
            /*
              if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
              $session->addFlash("warning", "Ihre Nachricht wurde weitergeleitet. Wir werden Sie schnellstmöglichst unter Ihrer Mailadresse $model->email kontaktieren!<br> Mit freundlichen Grüßen<br> Kanat Immobilien");
              } else {
              $session->addFlash("warning", "Ihre Nachricht konnte nicht weitergeleitet werden. Versuchen Sie es erneut!");
              }
              $this->redirect(["/site/index"]);
             */
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    /* Regelt die Logik der Impressumseite */

    public function actionAbout() {
        $this->layout = "main_kontakt";
        return $this->render('about');
    }

}
