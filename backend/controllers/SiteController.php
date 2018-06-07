<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\base\DynamicModel;
use yii\web\Session;
use kartik\widgets\Growl;
use common\models\LoginForm;
use common\models\User;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use backend\models\SignupForm;
use common\classes\error_handling;

class SiteController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
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

    /* Displays homepage. */

    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
?><?=

            Growl::widget([
                'type' => Growl::TYPE_GROWL,
                'title' => 'Warning',
                'icon' => 'glyphicon glyphicon-ok-sign',
                'body' => 'Da Sie offensichtlich den User gelöscht hatten, mit dem Sie sich eingeloggt haten, ist momentan kein User mehr angemeldet. Betätigen Sie bitte die Loginoption, rechts oben im Menu!',
                'showSeparator' => true,
                'delay' => 1500,
                'pluginOptions' => [
                    'showProgressbar' => true,
                    'placement' => [
                        'from' => 'top',
                        'align' => 'center',
                    ]
                ]
            ]);
        }
        return $this->render('index');
    }

    public function actionLogin() {
        $this->layout = "main_login";
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

    /* Legt einen neuen User an */

    public function actionSignup() {
        $this->layout = "reset_main";
        $modelUser = User::find()->all();
        foreach ($modelUser as $user) {
            $telefon = $user->telefon;
        }
        $session = new Session();
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    $session->addFlash('info', "Ein neuer Benutzer der Bezeichnung $model->username wurde soeben neu angelegt!");
                    return $this->goHome();
                }
            }
        }
        return $this->render('signup', [
                    'model' => $model,
                    'telefon' => $telefon
        ]);
    }

    /* Regelt die Logik der Passwortrücksetzung- T1 */

    public function actionRequestPasswordReset() {
        $this->layout = "reset_main";
        $session = new Session();
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $session->addFlash('success', "Überprüfen Sie ihren Maileingang mit einem Mailclient. Die neuen Zugangsdaten sind dort vermerkt");
                return $this->redirect('site/login');
            } else {
                $session->addFlash('error', "Sorry, we are unable to reset password for the provided email address.");
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

    /* Regelt das Ausloggen, indem es zum Frontend zurück rendert */

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->redirect(\Yii::$app->urlManagerFrontend->baseUrl . '/home');
    }

    /* Löscht einen User aus der Datenbank */

    public function actionDeluser() {
        try {
            $session = new Session();
            $connection = \Yii::$app->db;
            $record = $connection->createCommand('SELECT COUNT(id) FROM user');
            $user_count = $record->queryScalar();
            if ($user_count < 2) {
                $session->addFlash("warning", "Sie würden sich ausperren, da nur ein User im System registriert ist. Legen Sie einen neuen Benuzer an, bevor Sie dieses Feature aufrufen!");
                return $this->redirect(['/site/index']);
            }
            $session = new Session();
            $DynamicModel = new DynamicModel(['id_user']);
            $DynamicModel->addRule(['id_user'], 'integer');
            $DynamicModel->addRule(['id_user'], 'required');
            if ($DynamicModel->load(Yii::$app->request->post())) {
                $this->findModel_user($DynamicModel->id_user)->delete();
                $session->addFlash('info', "Der User mit der Id $DynamicModel->id_user wurde soeben gelöscht. Sie können sich mit dessen Logindaten zukünftig nicht mehr einloggen.");
                return $this->redirect(['/site/index']);
            } else {
                return $this->render('_form_userdelete', [
                            'DynamicModel' => $DynamicModel
                ]);
            }
        } catch (\Exception $error) {
            $go_back = "/site/index";
            error_handling::error_without_id($error, $go_back);
        }
    }

    protected function findModel_user($id) {
        try {
            $user = User::findOne($id); //findAll() gibt ein array aus Objekten zurück.findOne() gibt ein einzelnes Objekt zurück
            if ($user != NULL)
                return $user;
            else
                throw new NotFoundHttpException(Yii::t('app', 'Die Tabelle user konnte nicht geladen werden. Informieren Sie den Softwarehersteller'));
        } catch (\Exception $e) {
            throw new NotFoundHttpException(Yii::t('app', "$e"));
        }
    }

}
