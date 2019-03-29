<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

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
            <div class="col-md-3">
                <p>Ihre eingegebenen Daten:</p>
            </div>
            <div class="col-md-3">
                <?=
                $form->field($model, 'laenderkennung', ['addon' => [
                        'prepend' => ['content' => 'Länderkennung']]])->textInput(['maxlength' => true, 'value' => $laenderkennung, 'readOnly' => true])
                ?>
            </div>
            <div class="col-md-3">
                <?=
                $form->field($model, 'kontonummer', ['addon' => [
                        'prepend' => ['content' => 'Kontonummer']]])->textInput(['maxlength' => true, 'value' => $kontonummer, 'readOnly' => true])
                ?>
            </div>
            <div class="col-md-3">
                <?=
                $form->field($model, 'blz', ['addon' => [
                        'prepend' => ['content' => 'Bankleitzahl']]])->textInput(['maxlength' => true, 'value' => $blz, 'readOnly' => true])
                ?>
            </div>
            <div class="col-md-12">
                <p>Die ermittelten Webservicedaten:</p>
            </div>
            <div class="col-md-4">
                <?=
                $form->field($model, 'institut', ['addon' => [
                        'prepend' => ['content' => 'Bankinstitut']]])->textInput(['maxlength' => true, 'value' => $institut, 'readOnly' => true])
                ?>
            </div>  
            <div class="col-md-4">
                <?=
                $form->field($model, 'bic', ['addon' => [
                        'prepend' => ['content' => 'BIC']]])->textInput(['maxlength' => true, 'value' => $bic, 'readOnly' => true])
                ?>
            </div>         
            <div class="col-md-4">
                <?=
                $form->field($model, 'iban', ['addon' => [
                        'prepend' => ['content' => 'IBAN']]])->textInput(['maxlength' => true, 'value' => $iban, 'readOnly' => true])
                ?>             
            </div> 
            <div class="col-md-12">
                <p>KundenId: <?= $id ?></p>
            </div> 
        </div>
    </div>
</div>
<div class="form-group">              
    <?= Html::submitButton(Yii::t('app', 'Weiter'), ['class' => 'btn btn-info']) ?>
    <?= Html::a(Yii::t('app', 'Cancel'), ['/site/index'], ['class' => 'btn btn-danger']) ?>
</div>
<?php ActiveForm::end(); ?>


