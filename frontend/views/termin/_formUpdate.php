<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\widgets\DateTimePicker;
use kartik\widgets\DatePicker;
use yii\web\JsExpression;
use frontend\models\Immobilien;
use backend\models\LGeschlecht;
use common\models\User;
?>

<div class="immobilien-form"><br><br><br>

    <?php
    $this->title = Yii::t('app', 'Aktualisiere {modelClass}: ', ['modelClass' => 'Termin',]) . ' ' . $model->id;
    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'formConfig' => [
                    'showLabels' => false
    ]]);
    $link = \Yii::$app->urlManagerBackend->baseUrl . '/home';
    ?>
    <?= $form->errorSummary($model); ?>

    <center><h1><?= Html::encode($this->title) ?></h1></center>
    <div class="row">
        <div class="col-md-12">
            <div class="box-body">
                <div class="box-group" id="accordion">
                    <!--  End of Defining global CSS rules-->
                    <!-- Beginn des Personenformulars-->
                    <div class="panel box box-secondary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                    Kundenangaben
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse in"> <!-- !weist der Column die JS-Id zu!-->
                            <div class="row">
                                <div class="col-md-4">                                  
                                    <?=
                                    $form->field($modelKunde, 'geschlecht', ['addon' => [
                                            'prepend' => ['content' => 'Geschlecht']]])->widget(\kartik\widgets\Select2::classname(), [
                                        'data' => \yii\helpers\ArrayHelper::map(LGeschlecht::find()->asArray()->all(), 'id', 'typus'),
                                        'options' => ['placeholder' => Yii::t('app', 'Geschlecht wählen')],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <?=
                                    $form->field($modelKunde, 'vorname', ['addon' => ['prepend' => ['content' => 'Vorname']]])->textInput();
                                    ?>   
                                </div>
                                <div class="col-md-4">
                                    <?=
                                    $form->field($modelKunde, 'nachname', ['addon' => ['prepend' => ['content' => 'Nachname']]])->textInput();
                                    ?>   
                                </div>
                                <div class="col-md-4">
                                    <?=
                                    $form->field($modelKunde, 'geburtsdatum', ['addon' => [
                                            'prepend' => ['content' => 'Geburtsdatum']]])->widget(DatePicker::classname(), [
                                        'options' => ['placeholder' => 'Datum eingeben'],
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'yyyy-mm-dd',
                                        ]
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <?php
                                    $route = Url::to(['auswahl']);
                                    ?>
                                    <?=
                                    $form->field($modelKunde, 'l_plz_id', ['addon' => [
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
                                    $form->field($modelKunde, 'stadt', ['addon' => [
                                            'prepend' => ['content' => 'Stadt']]])->textInput(['maxlength' => true, 'placeholder' => 'Applikation füllt die Stadt gemäß der Postleitzahl', 'readonly' => true])
                                    ?>

                                </div>
                                <div class="col-md-4">
                                    <?=
                                    $form->field($modelKunde, 'strasse', ['addon' => [
                                            'prepend' => ['content' => 'Strasse'], 'append' => ['content' => 'Hausnummer']]])->textInput();
                                    ?>  
                                </div>
                                <div class="col-md-4">
                                    <?=
                                    $form->field($modelKunde, 'email', ['addon' => [
                                            'prepend' => ['content' => 'Email'], 'append' => ['content' => 'muss valide sein']]])->textInput();
                                    ?>  
                                </div>
                                <div class="col-md-4">
                                    <?=
                                    $form->field($modelKunde, 'telefon', ['addon' => [
                                            'prepend' => ['content' => 'Telefon'], 'append' => ['content' => 'Handy bevorzugt']]])->textInput();
                                    ?>  
                                </div>
                            </div>
                        </div>
                        <!--  Ending global CSS rules-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terminangaben-->
    <div class="row">
        <div class="col-md-12">
            <div class="box-body">
                <div class="box-group" id="accordion">
                    <!--  End of Defining global CSS rules-->
                    <!-- Beginn des Personenformulars-->
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                    Angaben zum Termin
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in"> <!-- !weist der Column die JS-Id zu!-->
                            <div class="row">
                                <div class="col-md-6">
                                    <?=
                                    $form->field($model, 'uhrzeit', ['addon' => [
                                            'prepend' => ['content' => 'Uhrzeit und Datum']]])->widget(DateTimePicker::classname(), [
                                        'options' => ['placeholder' => 'Zeitpunkt eingeben'],
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'yyyy-mm-dd hh:ii:ss',
                                        ]
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?=
                                    $form->field($model, 'Relevanz', ['addon' => [
                                            'prepend' => ['content' => 'An Abwicklung interesiert']]])->widget(\kartik\checkbox\CheckboxX::classname(), [
                                        'autoLabel' => false,
                                    ])->label(false);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!--  Ending global CSS rules-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box-body">
                <div class="row">                                                    
                    <?php
                    ?>
                    <div class="col-md-6">
                        <?=
                        $form->field($model, 'aktualisiert_von', ['addon' => [
                                'prepend' => ['content' => 'aktualisert von']]])->widget(\kartik\widgets\Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map(User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
                            'options' => ['placeholder' => Yii::t('app', 'Makler selektieren')],
                        ]);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?=
                        $form->field($model, 'aktualisiert_am', ['addon' => [
                                'prepend' => ['content' => 'aktualisert_am'], 'append' => ['content' => 'Diese Option übernimmt die Applikation']]])->widget(\kartik\datecontrol\DateControl::classname(), [
                            'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                            'disabled' => true,
                            'saveFormat' => 'php:Y-m-d H:i:s',
                            'ajaxConversion' => true,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box-body">
                <?php if (Yii::$app->controller->action->id != 'save-as-new'): ?>
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Erzeugen') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?php endif; ?>
                <?= Html::a(Yii::t('app', 'Abbruch'), $link, ['class' => 'btn btn-danger']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

