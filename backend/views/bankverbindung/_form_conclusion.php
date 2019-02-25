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
    $this->title = Yii::t('app', 'Zusammenfassung');
    ?>
    <div class="page-header">
        <br><br><center>
            <h1><?= Html::encode($this->title) ?></h1></center>
    </div>
    <div class="jumbotron">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>Folgende Bankdaten werden nach Ihrem Submit abgespeichert:</p>
                </div>
                <div class="col-md-4">
                    <p>Länderkennung: <?= $laenderkennung ?></p>
                </div>
                <div class="col-md-4">
                    <p>Kontonummer: <?= $kontonummer ?></p>
                </div>
                <div class="col-md-4">
                    <p>Bankleitzahl: <?= $blz ?></p>
                </div>
                <div class="col-md-4">
                    <p>Institut: <?= $institut ?></p>
                </div>  
                <div class="col-md-4">
                    <p>BIC: <?= $bic ?></p>
                </div>         
                <div class="col-md-4">
                    <p>IBAN: <?= $iban ?></p>
                </div>  
            </div>
        </div>
    </div>
    <div class="form-group">              
        <?= Html::submitButton(Yii::t('app', 'Weiter'), ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['/site/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>

