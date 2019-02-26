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

    <?= $form->errorSummary($model); ?>
    <br><br>
    <div class="col-md-12">
        <?=
        $form->field($model, 'laenderkennung', ['addon' => [
                'prepend' => ['content' => 'Länderkennung'], 'append' => ['content' => 'in der manuellen Eingabe ist eine DropDownBox. Der Value für Deutschland ist DE']]])->textInput(['maxlength' => true, 'placeholder' => 'Bitte die Länderkennung eingeben'])
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'blz', ['addon' => [
                'prepend' => ['content' => 'BLZ']]])->textInput(['maxlength' => true, 'placeholder' => 'Bitte die Bankleitzahl eingeben'])
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'kontoNr', ['addon' => [
                'prepend' => ['content' => 'Kontonummer']]])->textInput(['maxlength' => true, 'placeholder' => 'Bitte die Kontonummer eingeben'])
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'iban', ['addon' => [
                'prepend' => ['content' => 'IBAN'], 'append' => ['content' => 'berechnet die Applikation']]])->textInput(['maxlength' => true, 'readonly' => true])
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'bic', ['addon' => [
                'prepend' => ['content' => 'BIC'], 'append' => ['content' => 'ermittelt ein Webservice']]])->textInput(['maxlength' => true, 'readonly' => true])
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'institut', ['addon' => [
                'prepend' => ['content' => 'Institut'], 'append' => ['content' => 'ermittelt ein Webservice']]])->textInput(['maxlength' => true, 'readonly' => true])
        ?>
    </div>
    <div class="form-group">
        <?php if (Yii::$app->controller->action->id != 'save-as-new'): ?>
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['/site/index'], ['class' => 'btn btn-danger']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
