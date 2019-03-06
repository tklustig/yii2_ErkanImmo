<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="form-bankverbindung-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'laenderkennung')->textInput(['maxlength' => true, 'placeholder' => 'Laenderkennung']) ?>

    <?= $form->field($model, 'institut')->textInput(['maxlength' => true, 'placeholder' => 'Institut']) ?>

    <?= $form->field($model, 'blz')->textInput(['placeholder' => 'Blz']) ?>

    <?= $form->field($model, 'kontoNr')->textInput(['placeholder' => 'KontoNr']) ?>

    <?php /* echo $form->field($model, 'iban')->textInput(['maxlength' => true, 'placeholder' => 'Iban']) */ ?>

    <?php /* echo $form->field($model, 'bic')->textInput(['maxlength' => true, 'placeholder' => 'Bic']) */ ?>

    <?php /* echo $form->field($model, 'kunde_id')->widget(\kartik\widgets\Select2::classname(), [
      'data' => \yii\helpers\ArrayHelper::map(\backend\models\Kunde::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
      'options' => ['placeholder' => Yii::t('app', 'Choose Kunde')],
      'pluginOptions' => [
      'allowClear' => true
      ],
      ]); */ ?>

    <?php /* echo $form->field($model, 'angelegt_am')->widget(\kartik\datecontrol\DateControl::classname(), [
      'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
      'saveFormat' => 'php:Y-m-d H:i:s',
      'ajaxConversion' => true,
      'options' => [
      'pluginOptions' => [
      'placeholder' => Yii::t('app', 'Choose Angelegt Am'),
      'autoclose' => true,
      ]
      ],
      ]); */ ?>

    <?php /* echo $form->field($model, 'aktualisiert_am')->widget(\kartik\datecontrol\DateControl::classname(), [
      'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
      'saveFormat' => 'php:Y-m-d H:i:s',
      'ajaxConversion' => true,
      'options' => [
      'pluginOptions' => [
      'placeholder' => Yii::t('app', 'Choose Aktualisiert Am'),
      'autoclose' => true,
      ]
      ],
      ]); */ ?>

    <?php /* echo $form->field($model, 'angelegt_von')->widget(\kartik\widgets\Select2::classname(), [
      'data' => \yii\helpers\ArrayHelper::map(\backend\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
      'options' => ['placeholder' => Yii::t('app', 'Choose User')],
      'pluginOptions' => [
      'allowClear' => true
      ],
      ]); */ ?>

    <?php /* echo $form->field($model, 'aktualisiert_von')->widget(\kartik\widgets\Select2::classname(), [
      'data' => \yii\helpers\ArrayHelper::map(\backend\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
      'options' => ['placeholder' => Yii::t('app', 'Choose User')],
      'pluginOptions' => [
      'allowClear' => true
      ],
      ]); */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
<?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
