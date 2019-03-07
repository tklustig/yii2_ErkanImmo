<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="form-immobilien-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'bezeichnung')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'strasse')->textInput(['maxlength' => true, 'placeholder' => 'Strasse']) ?>

    <?= $form->field($model, 'wohnflaeche')->textInput(['placeholder' => 'Wohnflaeche']) ?>

    <?= $form->field($model, 'raeume')->textInput(['placeholder' => 'Raeume']) ?>

    <?php /* echo $form->field($model, 'geldbetrag')->textInput(['maxlength' => true, 'placeholder' => 'Geldbetrag']) */ ?>

    <?php /* echo $form->field($model, 'l_plz_id')->textInput(['placeholder' => 'L Plz']) */ ?>

    <?php /* echo $form->field($model, 'l_stadt_id')->widget(\kartik\widgets\Select2::classname(), [
      'data' => \yii\helpers\ArrayHelper::map(\backend\models\LStadt::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
      'options' => ['placeholder' => Yii::t('app', 'Choose L stadt')],
      'pluginOptions' => [
      'allowClear' => true
      ],
      ]); */ ?>

    <?php /* echo $form->field($model, 'user_id')->widget(\kartik\widgets\Select2::classname(), [
      'data' => \yii\helpers\ArrayHelper::map(\backend\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
      'options' => ['placeholder' => Yii::t('app', 'Choose User')],
      'pluginOptions' => [
      'allowClear' => true
      ],
      ]); */ ?>

    <?php /* echo $form->field($model, 'l_art_id')->widget(\kartik\widgets\Select2::classname(), [
      'data' => \yii\helpers\ArrayHelper::map(\backend\models\LArt::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
      'options' => ['placeholder' => Yii::t('app', 'Choose L art')],
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
