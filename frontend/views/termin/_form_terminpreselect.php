<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Makler wählen';
$this->params['breadcrumbs'][] = $this->title;
$link = \Yii::$app->urlManagerBackend->baseUrl . '/home';
?>
<div class="jumbotron">
    <div class="container">   
        <div class="page-header"><h2><?= Html::encode($this->title) ?><small>Wählen Sie den Makler über die DropDown-Box</small></h2></div>
        <div class="row">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <div class="col-md-12">
                <?=
                $form->field($DynamicModel, 'id_user')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->asArray()->all(), 'id', 'username'),
                    'options' => ['placeholder' => Yii::t('app', 'Bitte Makler selektieren')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label(false);
                ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
            <?= Html::a('<span class="fa fa-rotate-left"> zurück', $link, ['class' => 'btn btn-default', 'title' => 'rendert zum Backend zurück', 'data' => ['pjax' => '0']]); ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

