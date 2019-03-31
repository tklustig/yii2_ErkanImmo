<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
?>

<div class="form-bankverbindung-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'formConfig' => [
                    'showLabels' => false
                ]
    ]);
    ?>

    <div class="col-md-6">
        <?=
        $form->field($model, 'laenderkennung', ['addon' => [
                'prepend' => ['content' => 'Länderkennung']]])->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(backend\models\LLaenderkennung::find()->asArray()->all(), 'code', 'code'),
            'options' => ['placeholder' => Yii::t('app', 'Länderkennung wählen')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(false);
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'kunde_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(frontend\models\Kunde::find()->orderBy('id')->asArray()->all(), 'id', 'nachname'),
            'options' => ['placeholder' => Yii::t('app', 'Kunde selektieren')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>        
    </div>
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
