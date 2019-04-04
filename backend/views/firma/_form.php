<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Firma */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="firma-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id')->textInput(['placeholder' => 'Id']) ?>

    <?= $form->field($model, 'firmenname')->textInput(['maxlength' => true, 'placeholder' => 'Firmenname']) ?>

    <?= $form->field($model, 'l_rechtsform_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\backend\models\LRechtsform::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose L rechtsform')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'strasse')->textInput(['maxlength' => true, 'placeholder' => 'Strasse']) ?>

    <?= $form->field($model, 'hausnummer')->textInput(['placeholder' => 'Hausnummer']) ?>

    <?= $form->field($model, 'l_plz_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(frontend\models\LPlz::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose L plz')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'ort')->textInput(['maxlength' => true, 'placeholder' => 'Ort']) ?>

    <?= $form->field($model, 'geschaeftsfuehrer')->textInput(['maxlength' => true, 'placeholder' => 'Geschaeftsfuehrer']) ?>

    <?= $form->field($model, 'prokurist')->textInput(['maxlength' => true, 'placeholder' => 'Prokurist']) ?>

    <?= $form->field($model, 'umsatzsteuerID')->textInput(['maxlength' => true, 'placeholder' => 'UmsatzsteuerID']) ?>

    <?= $form->field($model, 'anzahlMitarbeiter')->textInput(['placeholder' => 'AnzahlMitarbeiter']) ?>
    <div class="form-group">
    <?php if(Yii::$app->controller->action->id != 'save-as-new'): ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?php endif; ?>
    <?php if(Yii::$app->controller->action->id != 'create'): ?>
        <?= Html::submitButton(Yii::t('app', 'Save As New'), ['class' => 'btn btn-info', 'value' => '1', 'name' => '_asnew']) ?>
    <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->referrer , ['class'=> 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
