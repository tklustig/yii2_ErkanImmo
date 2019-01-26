<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\TerminSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="besichtigungstermin-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uhrzeit') ?>

    <?= $form->field($model, 'Relevanz') ?>

    <?= $form->field($model, 'angelegt_am') ?>

    <?= $form->field($model, 'aktualisiert_am') ?>

    <?php // echo $form->field($model, 'angelegt_von') ?>

    <?php // echo $form->field($model, 'aktualisiert_von') ?>

    <?php // echo $form->field($model, 'Immobilien_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
