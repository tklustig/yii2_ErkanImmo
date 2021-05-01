<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\Session;

//Hier werden alle Flashnachrichten ausgegeben
$session = Yii::$app->session;
$link = \Yii::$app->urlManagerFrontend->baseUrl . '/home';
if (!empty($session->getAllFlashes())) {
    foreach ($session->getAllFlashes() as $flash) {
        foreach ($flash as $ausgabe) {
            ?><?=
            Alert::widget([
                'type' => Alert::TYPE_SUCCESS,
                'title' => 'Information',
                'icon' => 'glyphicon glyphicon-exclamation-sign',
                'body' => $ausgabe,
                'showSeparator' => true,
                'delay' => false
            ]);
        }
    }
}
$this->title = 'Sign In';
$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];
$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Admin</b>LTE</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in or reset Password or go  <?= Html::a('back', $link, ['title' => 'zurück']) ?></p>
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form->field($model, 'username', $fieldOptions1)->label(false)->textInput(['placeholder' => $model->getAttributeLabel('username')]);
        ?>

        <?= $form->field($model, 'password', $fieldOptions2)->label(false)->passwordInput(['placeholder' => $model->getAttributeLabel('password')]);
        ?>

        <div class="row">
            <div class="col-xs-12">
                <?= Html::submitButton('Sign in', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
        </div>
        <?php
        ActiveForm::end();
        ?>
        <!--        <div class="social-auth-links text-center">
                   <p>- OR -</p>
                   <a href="https://de-de.facebook.com/login/" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in
                       using Facebook</a>
                   <a href="https://accounts.google.com/signin/v2/identifier?hl=de&flowName=GlifWebSignIn&flowEntry=ServiceLogin" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-google-plus"></i> Sign
                       in using Google+</a>
       
               </div> -->
        <br>
        <?= Html::a('I forgot my password respectively username', ['site/request-password-reset'], ['class' => 'btn btn-block btn-danger']) ?>
    </div>
</div>
