<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Besichtigungstermin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="besichtigungstermin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uhrzeit')->textInput() ?>

    <?= $form->field($model, 'Relevanz')->textInput() ?>

    <?= $form->field($model, 'angelegt_am')->textInput() ?>

    <?= $form->field($model, 'aktualisiert_am')->textInput() ?>

    <?= $form->field($model, 'angelegt_von')->textInput() ?>

    <?= $form->field($model, 'aktualisiert_von')->textInput() ?>

    <?= $form->field($model, 'Immobilien_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
