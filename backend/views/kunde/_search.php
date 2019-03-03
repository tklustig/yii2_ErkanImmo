<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\KundeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-kunde-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>
    <div class="col-md-12">
        <?=
        $form->field($model, 'choice_date')->radioList([0 => 'Vorher', 1 => 'Nachher'], ['itemOptions' => ['class' => 'choiceRadio']])->label('Grenzen Sie über diese beiden Radio Buttons Ihre Suche ein');
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'angelegt_am')->widget(\kartik\datetime\DateTimePicker::className(), [
            'type' => kartik\datetime\DateTimePicker::TYPE_COMPONENT_PREPEND,
            'pluginOptions' =>
            [
                'autoclose' => true,
                'todayHighlight' => true,
                'format' => 'yyyy-mm-dd HH:ii:ss'
            ],
            'options' => [
                'placeholder' => Yii::t('app', 'angelegt am ...'),],
        ])->label(false);
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'aktualisiert_am')->widget(\kartik\datetime\DateTimePicker::className(), [
            'type' => kartik\datetime\DateTimePicker::TYPE_COMPONENT_PREPEND,
            'pluginOptions' =>
            [
                'autoclose' => true,
                'todayHighlight' => true,
                'format' => 'yyyy-mm-dd HH:ii:ss'
            ],
            'options' => [
                'placeholder' => Yii::t('app', 'aktualisiert am ...'),],
        ])->label(false);
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'geburtsdatum')->widget(\kartik\date\DatePicker::className(), [
            'type' => kartik\datetime\DateTimePicker::TYPE_COMPONENT_PREPEND,
            'pluginOptions' =>
            [
                'autoclose' => true,
                'todayHighlight' => true,
                'format' => 'yyyy-mm-dd'
            ],
            'options' => [
                'placeholder' => Yii::t('app', 'Geburtsdatum')],
        ])->label(false);
        ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'id')->textInput(['placeholder' => 'nach ID suchen']) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'l_plz_id')->textInput(['placeholder' => 'nach Plz suchen']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
