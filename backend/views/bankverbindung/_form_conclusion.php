<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
?>

<div class="immobilien-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'formConfig' => [
                    'showLabels' => false
    ]]);
    $this->title = Yii::t('app', 'Vorauswahl');
    ?>
    <div class="page-header">
        <br><br><center>
            <h1><?= Html::encode($this->title) ?></h1></center>
    </div>
    <div class="jumbotron">
        <div class="container">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Weiter'), ['class' => 'btn btn-info']) ?>
                <?= Html::a(Yii::t('app', 'Cancel'), ['/site/index'], ['class' => 'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

