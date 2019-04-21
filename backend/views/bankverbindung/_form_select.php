<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
?>

<?php
$modelKunde = frontend\models\Kunde::find()->all();
$arrayOfBankverbindung = array();
foreach ($modelKunde as $item) {
    if ($item->bankverbindung_id == null)
        array_push($arrayOfBankverbindung, $item->id);
}
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
        <span class="badge badge-light">Es werden in der DropDownbox nur diejenigen Kunden zur Auswahl angeboten, für welche im System bisher keinerlei Bankdaten hinterlegt wurden </span> 
    </div>
    <div class="jumbotron">
        <div class="container">
            <div class="col-md-12">
                <p>Bitte treffen Sie eine Vorauswahl, für welchen Kunden Sie die Bankdaten eingeben wollen..</p>
                <?=
                $form->field($DynamicModel, 'kunde', ['addon' => [
                        'prepend' => ['content' => 'Typ']]])->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(frontend\models\Kunde::find()->orderBy('id')->where(['id' => $arrayOfBankverbindung])->asArray()->all(), 'id', 'nachname'),
                    'options' => ['placeholder' => Yii::t('app', 'Kunde wählen')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Weiter'), ['class' => 'btn btn-info']) ?>
                <?= Html::a(Yii::t('app', 'Cancel'), ['/site/index'], ['class' => 'btn btn-danger']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>




