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
    <br><br><br>
    <div class="col-md-4">
        <?=
        $form->field($model, 'iban', ['addon' => [
                'prepend' => ['content' => 'IBAN']]])->textInput(['maxlength' => true, 'placeholder' => 'IBAN ermittelt die Applikation'])
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'bic', ['addon' => [
                'prepend' => ['content' => 'BIC']]])->textInput(['maxlength' => true, 'placeholder' => 'BIC ermittelt die Applikation'])
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'institut', ['addon' => [
                'prepend' => ['content' => 'Institut']]])->textInput(['maxlength' => true, 'placeholder' => 'Institut ermittelt die Applikation'])
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'blz', ['addon' => [
                'prepend' => ['content' => 'BLZ'], 'append' => ['content' => 'ermittelt die Applikation']]])->textInput(['maxlength' => true, 'placeholder' => 'BLZ ermittelt die Applikation', 'readonly' => true])
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'kontoNr', ['addon' => [
                'prepend' => ['content' => 'Kontonummer'], 'append' => ['content' => 'ermittelt die Applikation']]])->textInput(['maxlength' => true, 'placeholder' => 'Kontonummer ermittelt die Applikation', 'readonly' => true])
        ?>
    </div>
    <div class="form-group">
        <?php if (Yii::$app->controller->action->id != 'save-as-new'): ?>
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
        <?php if (Yii::$app->controller->action->id != 'create'): ?>
            <?= Html::submitButton(Yii::t('app', 'Save As New'), ['class' => 'btn btn-info', 'value' => '1', 'name' => '_asnew']) ?>
        <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['/site/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

