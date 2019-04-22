<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
?>

<div class="mailserver-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'formConfig' => [
                    'showLabels' => false
                ]
    ]);
    ?>

    <?= $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-md-6">

            <?=
            $form->field($model, 'serverURL', ['addon' => [
                    'prepend' => ['content' => 'Url (optional)'], 'append' => ['content' => 'nur IPv4 Adressen werden akzeptiert']]])->textInput(['maxlength' => true, 'placeholder' => 'ServerURL'])
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'serverHost', ['addon' => [
                    'prepend' => ['content' => 'Host'], 'append' => ['content' => 'gültigen DNS Name eingeben']]])->textInput(['maxlength' => true, 'placeholder' => 'ServerHost'])
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'username', ['addon' => [
                    'prepend' => ['content' => 'Benutzername']]])->textInput(['maxlength' => true, 'placeholder' => 'Username'])
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'password', ['addon' => [
                    'prepend' => ['content' => 'Passwort'], 'append' => ['content' => 'wird nicht verschlüsselt']]])->passwordInput(['maxlength' => true, 'placeholder' => 'Password'])
            ?>
        </div><div class="col-md-6">
            <?=
            $form->field($model, 'port', ['addon' => [
                    'prepend' => ['content' => 'Port'], 'append' => ['content' => 'z.B. 587']]])->textInput(['placeholder' => 'Port'])
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'encryption', ['addon' => [
                    'prepend' => ['content' => 'Verschlüsselungsart(optional, aber üblich)'], 'append' => ['content' => 'z.B tls']]])->textInput(['maxlength' => true, 'placeholder' => 'Encryption'])
            ?>
        </div>
        <div class="col-md-12">
            <?=
            $form->field($model, 'useEncryption', ['addon' => [
                    'prepend' => ['content' => 'Verschlüsselung aktiv']]])->widget(\kartik\checkbox\CheckboxX::classname(), [
                'autoLabel' => false
            ])->label(false);
            ?>
        </div>
    </div>

    <div class="form-group">
        <?php if (Yii::$app->controller->action->id != 'save-as-new'): ?>
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['/mailserver/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
