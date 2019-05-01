<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use yii\web\JsExpression;
?>
<div class="kunde-form">
    <?php
    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'formConfig' => [
                    'showLabels' => true
                ]
    ]);
    ?>
    <?= $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-md-12">
            <?=
            $form->field($model, 'bankverbindung_id', ['addon' => [
                    'prepend' => ['content' => 'Bankinstitut']]])->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(backend\models\Bankverbindung::find()->asArray()->all(), 'id', 'institut'),
                'options' => ['placeholder' => Yii::t('app', 'Bankverbindung selektieren')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-12">
            <?=
            /* 22.11.2017/tklustig/Initialisiert das Upload-Formular.Damit das multiple uploading klappt,muss die property als Array eingebunden werden
              In Zeile 61 wird an eine statische URL zurück gerendert. Dass koennte irgendwann einmal eine Fehlerquelle darstellen und muss dann behoben werden
             */

            $form->field($modelDateianhang, 'attachement[]')->widget(FileInput::classname(), [
                'options' => ['multiple' => true],
                'pluginOptions' => ['allowedFileExtensions' => ['jpg', 'jpeg', 'tiff', 'gif', 'bmp', 'png', 'svg', 'ico']],
            ]);
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($modelDateianhang, 'l_dateianhang_art_id')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\frontend\models\LDateianhangArt::find()->orderBy('id')->asArray()->all(), 'id', 'bezeichnung'),
                'options' => ['placeholder' => Yii::t('app', 'Dateianhangsart')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-4">
            <?php
            $route = Url::to(['auswahlk']);
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
        <div class="col-md-4">
            <?=
            $form->field($model, 'geschlecht', ['addon' => [
                    'prepend' => ['content' => 'Geschlecht']]])->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\LGeschlecht::find()->asArray()->all(), 'id', 'typus'),
                'options' => ['placeholder' => Yii::t('app', 'Geschlecht selektieren')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'vorname')->textInput(['placeholder' => 'Vorname']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'nachname')->textInput(['maxlength' => true, 'placeholder' => 'Nachname']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'stadt')->textInput(['maxlength' => true, 'placeholder' => 'Stadt', 'id' => 'immobilien-stadt']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'strasse')->textInput(['maxlength' => true, 'placeholder' => 'Strasse']) ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'geburtsdatum')->widget(\kartik\date\DatePicker::className(), [
                'type' => kartik\datetime\DateTimePicker::TYPE_COMPONENT_PREPEND,
                'pluginOptions' =>
                [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'yyyy-mm-dd'
                ],
                'options' => [
                    'placeholder' => Yii::t('app', 'Geburtsdatum')],
            ])->label('Geburtsdatum');
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'telefon')->textInput(['maxlength' => true, 'placeholder' => 'Strasse']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => 'Strasse']) ?>
        </div>

        <div class="col-md-4">
            <?=
            $form->field($model, 'aktualisiert_von')->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
                'options' => ['placeholder' => Yii::t('app', 'Choose User')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-12">
            <?=
            $form->field($model, 'solvenz')->widget(\kartik\checkbox\CheckboxX::classname(), [
                'autoLabel' => true
            ])->label(false);
            ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['/kunde/index'], ['class' => 'btn btn-danger']) ?>
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
   $('#immobilien-stadt').attr('value',data.ort);
   });
   });
        

JS;
$this->registerJS($script);
?>