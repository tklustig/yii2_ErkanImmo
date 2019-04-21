<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;
?>

<div class="firma-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'formConfig' => [
                    'showLabels' => false
                ]
    ]);
    ?>

    <?= $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-md-6">
            <?=
            $form->field($model, 'l_rechtsform_id', ['addon' => [
                    'prepend' => ['content' => 'Rechtsform']]])->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\LRechtsform::find()->orderBy('id')->asArray()->all(), 'id', 'typus'),
                'options' => ['placeholder' => Yii::t('app', 'Rechtsform wählen')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'firmenname', ['addon' => [
                    'prepend' => ['content' => 'Firmenname']]])->textInput(['maxlength' => true])
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'strasse', ['addon' => [
                    'prepend' => ['content' => 'Strasse']]])->textInput(['maxlength' => true])
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'hausnummer', ['addon' => [
                    'prepend' => ['content' => 'Hausnummer']]])->textInput();
            ?>
        </div>
        <div class="col-md-3">
            <?php
            $route = Url::to(['auswahl']);
            ?><?=
            $form->field($model, 'l_plz_id', ['addon' => [
                    'prepend' => ['content' => 'Plz']]])->widget(\kartik\widgets\Select2::classname(), [
                'options' => ['placeholder' => Yii::t('app', 'Postleitzahl wählen'),
                    'id' => 'zip_code',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => $route,
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(bewerber) { return bewerber.text; }'),
                    'templateSelection' => new JsExpression('function(bewerber) { return bewerber.text; }'),
                ],
            ])->label(false);
            ?>

        </div> 
        <div class="col-md-3">
            <?=
            $form->field($model, 'ort', ['addon' => [
                    'prepend' => ['content' => 'Stadt']]])->textInput(['maxlength' => true, 'placeholder' => 'Applikation füllt den Ort gemäß der Postleitzahl', 'readOnly' => true])
            ?>

        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'geschaeftsfuehrer', ['addon' => [
                    'prepend' => ['content' => 'Geschäftsführer']]])->textInput(['maxlength' => true])
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'bankdaten')->widget(\dosamigos\ckeditor\CKEditor::className(), [
                'preset' => 'full', 'clientOptions' => ['height' => 200],
            ])
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'prokurist', ['addon' => [
                    'prepend' => ['content' => 'Prokurist']]])->textInput(['maxlength' => true])
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'umsatzsteuerID', ['addon' => [
                    'prepend' => ['content' => 'UmsatzsteuerID']]])->textInput(['maxlength' => true])
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'anzahlMitarbeiter', ['addon' => [
                    'prepend' => ['content' => 'Mitarbeiteranzahl']]])->widget(kartik\slider\Slider::classname(), [
                'pluginOptions' => [
                    'min' => 0,
                    'max' => 50,
                    'step' => 1,
                    'handle' => 'triangle',
                    'tooltip' => 'always'
                ]
            ]);
            ?>
        </div>
    </div>
    <div class="form-group">
        <?php if (Yii::$app->controller->action->id != 'save-as-new'): ?>
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->referrer, ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$url = Url::to(['plz/get-city-province']);

$script = <<< JS
        $('#zip_code').change(function(){
        var zipId=$(this).val();
       $.get('$url',{zipId:zipId},function(data){
   var data=$.parseJSON(data);
   alert(data.plz+" entspricht der Stadt "+data.ort+"! Die Id ist "+zipId);
   $('#firma-ort').attr('value',data.ort);
   });
   });

JS;
$this->registerJS($script);
?>
