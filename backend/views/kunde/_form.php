<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
?>

<div class="kunde-form">
    <?php
    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'formConfig' => [
                    'showLabels' => true
    ]]);
    ?>
    <?= $form->errorSummary($model); ?>
    <div class="col-md-12">
        <?= $form->field($model, 'l_plz_id')->textInput(['value' => $plz]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'geschlecht')->textInput(['maxlength' => true, 'placeholder' => 'Geschlecht']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'vorname')->textInput(['maxlength' => true, 'placeholder' => 'Vorname']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'nachname')->textInput(['maxlength' => true, 'placeholder' => 'Nachname']) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'stadt')->textInput(['maxlength' => true, 'placeholder' => 'Stadt']) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'strasse')->textInput(['maxlength' => true, 'placeholder' => 'Strasse']) ?>
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
    <div class="col-md-4">
        <?=
        $form->field($model, 'bankverbindung_id', ['addon' => [
                'prepend' => ['content' => 'Bankinstitut']]])->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(backend\models\Bankverbindung::find()->asArray()->all(), 'id', 'institut'),
            'options' => ['placeholder' => Yii::t('app', 'Bankverbindung selektieren')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>

    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'aktualisiert_von')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
            'options' => ['placeholder' => Yii::t('app', 'Choose User')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    </div>
    <div class="col-md-12">
        <?=
        $form->field($model, 'solvenz')->widget(\kartik\checkbox\CheckboxX::classname(), [
            'autoLabel' => true
        ])->label(false);
        ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->referrer, ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
