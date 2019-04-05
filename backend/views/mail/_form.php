<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Mail */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="mail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id')->textInput(['placeholder' => 'Id']) ?>

    <?= $form->field($model, 'id_mailserver')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\backend\models\Mailserver::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Mailserver')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'mail_from')->textInput(['maxlength' => true, 'placeholder' => 'Mail From']) ?>

    <?= $form->field($model, 'mail_to')->textInput(['maxlength' => true, 'placeholder' => 'Mail To']) ?>

    <?= $form->field($model, 'mail_cc')->textInput(['maxlength' => true, 'placeholder' => 'Mail Cc']) ?>

    <?= $form->field($model, 'mail_bcc')->textInput(['maxlength' => true, 'placeholder' => 'Mail Bcc']) ?>

    <?= $form->field($model, 'betreff')->textInput(['maxlength' => true, 'placeholder' => 'Betreff']) ?>

    <?= $form->field($model, 'bodytext')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->referrer , ['class'=> 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
