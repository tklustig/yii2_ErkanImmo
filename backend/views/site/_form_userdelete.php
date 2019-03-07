<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Benutzer löschen';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jumbotron">
    <div class="container">
        <div class="page-header"><h2><?= Html::encode($this->title) ?><small>Wählen Sie den Benutzer über die DropDown-Box</small></h2></div>
        <div class="row">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <div class="col-md-12">
                <?=
                $form->field($DynamicModel, 'id_user')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
                    'options' => ['placeholder' => Yii::t('app', '')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(false);
                ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Delete', ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

