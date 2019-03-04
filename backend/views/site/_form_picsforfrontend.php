<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Graphical Frontendinitialisation';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jumbotron">
    <div class="container">
        <div class="page-header"><h2><?= Html::encode($this->title) ?><small>Wählen Sie Ihre Frontendeinstellungen über die DropDown-Box</small></h2></div>
        <div class="row">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <div class="col-md-12">
                <?=
                $form->field($DynamicModel, 'bez')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(frontend\models\Dateianhang::find()->where(['l_dateianhang_art_id' => $max])->asArray()->all(), 'id', 'bezeichnung'),
                    'options' => ['placeholder' => Yii::t('app', 'Bezeichnung')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(false);
                ?>
            </div>
            <div class="col-md-12">
                <?=
                $form->field($DynamicModel, 'file')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(frontend\models\Dateianhang::find()->where(['l_dateianhang_art_id' => $max])->asArray()->all(), 'dateiname', 'dateiname'),
                    'options' => ['placeholder' => Yii::t('app', 'Filename')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(false);
                ?>
            </div>
            <div class="col-md-12">
                <?=
                $form->field($DynamicModel, 'art')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(frontend\models\LDateianhangArt::find()->where(['id' => $max])->asArray()->all(), 'id', 'bezeichnung'),
                    'options' => ['placeholder' => Yii::t('app', 'Art')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(false);
                ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
