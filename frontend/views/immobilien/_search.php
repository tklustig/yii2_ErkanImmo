<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Url;
use kartik\widgets\TouchSpin;
?>

<div class="form-immobilien-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['preview', 'searchPreview' => 1],
                'method' => 'get',
                'id' => 'dynamic-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'formConfig' => [
                    'showLabels' => false
                ]
    ]);
    ?>
    <?php
    $route = Url::to(['auswahl']);
    ?>
    <div class="col-md-6">
        <?=
        $form->field($model, 'l_plz_id', ['addon' => [
                'prepend' => ['content' => 'Plz']]])->widget(\kartik\widgets\Select2::classname(), [
            'options' => ['placeholder' => Yii::t('app', 'Plz wählen'),
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
    <div class="col-md-6">
        <?=
        $form->field($model, 'stadt', ['addon' => [
                'prepend' => ['content' => 'Stadt']]])->textInput(['maxlength' => true, 'placeholder' => 'Applikation füllt die Stadt gemäß der Postleitzahl', 'disabled' => true]);
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'choice_date')->radioList([0 => 'Höher als...', 1 => 'Weniger als...'], ['itemOptions' => ['class' => 'choiceRadio']])->hint('Grenzen Sie über diese beiden Radio Buttons Ihre Suche bzgl. der Kosten ein');
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'geldbetrag', ['addon' => [
                'prepend' => ['content' => 'Kosten'], 'append' => ['content' => '€']]])->textInput(['placeholder' => 'Kaufpreis oder Kaltmiete']);
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'wohnflaeche', ['addon' => [
                'prepend' => ['content' => 'Wohnfläche'], 'append' => ['content' => 'minimal(qm)']]])->widget(TouchSpin::classname(), [
            'options' => ['placeholder' => 'Von 20 bis 1000...'],
            'pluginOptions' => [
                'min' => '20',
                'max' => '1000'
            ]
        ]);
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'raeume')->widget(TouchSpin::classname(), [
            'options' => ['placeholder' => 'Von 1 bis 20...'],
            'pluginOptions' => [
                'verticalbuttons' => true,
                'verticalupclass' => 'glyphicon glyphicon-plus',
                'verticaldownclass' => 'glyphicon glyphicon-minus',
                'min' => '1',
                'max' => '20'
            ]
        ])->hint("Anzahl nutzbarer Räume");
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'balkon_vorhanden')->widget(\kartik\checkbox\CheckboxX::classname(), [
            'autoLabel' => true,
            'pluginOptions' => ['threeState' => false]
        ])->label(false);
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'fahrstuhl_vorhanden')->widget(\kartik\checkbox\CheckboxX::classname(), [
            'autoLabel' => true,
            'pluginOptions' => ['threeState' => false]
        ])->label(false);
        ?>
    </div>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
