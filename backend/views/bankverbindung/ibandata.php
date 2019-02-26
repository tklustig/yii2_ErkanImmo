<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
?>

<div class="bankverbindung-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'formConfig' => [
                    'showLabels' => false
    ]]);
    ?>
    <br><br><br>
    <div class="col-md-4">
        <?=
        $form->field($model, 'laenderkennung', ['addon' => [
                'prepend' => ['content' => 'Länderkennung']]])->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\backend\models\LLaenderkennung::find()->orderBy('code')->asArray()->all(), 'code', 'code'),
            'options' => ['placeholder' => Yii::t('app', 'Länderkennung')],
            'id' => 'idiban',
            'name' => 'abcfg',
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(false);
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'iban', ['addon' => [
                'prepend' => ['content' => 'IBAN'], 'append' => ['content' => '22-stellig']]])->textInput(['maxlength' => true, 'placeholder' => 'Bitte die IBAN eingeben'])
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'bic', ['addon' => [
                'prepend' => ['content' => 'BIC'], 'append' => ['content' => '11-stellig']]])->textInput(['maxlength' => true, 'placeholder' => 'BIC ermittelt die Applikation'])
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'institut', ['addon' => [
                'prepend' => ['content' => 'Institut']]])->textInput(['maxlength' => true, 'placeholder' => 'Bitte das Institut eingeben'])
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'blz', ['addon' => [
                'prepend' => ['content' => 'BLZ'], 'append' => ['content' => 'ermittelt die Applikation']]])->textInput(['maxlength' => true, 'placeholder' => 'BLZ ermittelt die Applikation', 'readonly' => true])
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'kontoNr', ['addon' => [
                'prepend' => ['content' => 'Kontonummer'], 'append' => ['content' => 'ermittelt die Applikation']]])->textInput(['maxlength' => true, 'readonly' => true])
        ?>
    </div>
    <div class="form-group">
        <?php if (Yii::$app->controller->action->id != 'save-as-new'): ?>
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'submit', 'value' => 'submitIbanData']) ?>
        <?php endif; ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

