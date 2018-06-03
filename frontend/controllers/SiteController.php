<?php

/*
  Gemäß dem MCV-Prinzip für die Logik der Applikation zuständig
  Klasse erstellt durch Gii
  Methoden: ©by Thomas Kipp, Klein - Buchholzer - Kirchweg 25, 30659 Hannover, http://tklustig.ddns.net:1025, tklustig.thomas@gmail.com
 */

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Session;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\DynamicModel;

class SiteController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
        $DynamicModel = new DynamicModel(['searching']);
        $DynamicModel->addRule(['searching'], 'string');
        if ($DynamicModel->load(Yii::$app->request->post())) {
            print_r("Script wurde in der Klasse " . get_class() . " angehalten");
            die();
        }
        return $this->render('index', [
                    'DynamicModel' => $DynamicModel,
        ]);
    }

    /* Regelt die Logik der Loginseite */

    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
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
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                $session->addFlash("warning", "Ihre Nachricht wurde weitergeleitet. Wir werden Sie schnellstmöglichst unter Ihrer Mailadresse $model->email kontaktieren!<br> Mit freundlichen Grüßen<br> Kanat Immobilien");
            } else {
                $session->addFlash("warning", "Ihre Nachricht konnte nicht weitergeleitet werden. Versuchen Sie es erneut!");
            }
            $this->redirect(["/site/index"]);
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    /* Regelt die Logik der Impressumseite */

    public function actionAbout() {
        return $this->render('about');
    }

    /* Regelt die Logik der Registrierung */

    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    /* Regelt die Logik der Passwortrücksetzung- T1 */

    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }
        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /* Regelt die Logik der Passwortrücksetzung- T2 */

    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }
        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

}
