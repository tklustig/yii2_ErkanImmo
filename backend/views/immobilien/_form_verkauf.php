<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use kartik\slider\Slider;
use raoul2000\widget\twbsmaxlength\TwbsMaxlength;
?>

<div class="immobilien-form"><br><br><br>

    <?php
    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'formConfig' => [
                    'showLabels' => false
    ]]);
    ?>
    <?= $form->errorSummary($model); ?>
    <?php
    if ($model->isNewRecord) {
        $this->title = Yii::t('app', 'Immobilie anlegen');
    } else {
        $this->title = Yii::t('app', 'Aktualisiere {modelClass}: ', [
                    'modelClass' => 'Immobilien',
                ]) . ' ' . $model->id;
    }
    ?>
    <center><h1><?= Html::encode($this->title) ?></h1></center>
    <!-- Beginn des Anhangformulars-->
    <!-- START ACCORDION & CAROUSEL-->
    <!--  Defining global CSS rules-->
    <?php if ($model->isNewRecord) { ?>
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
                                        Angaben zum Immobilienanhang
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in"> <!-- !weist der Column die JS-Id zu!-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <?=
                                        /* 22.11.2017/tklustig/Initialisiert das Upload-Formular.Damit das multiple uploading klappt,muss die property als Array eingebunden werden
                                          In Zeile 61 wird an eine statische URL zurück gerendert. Dass koennte irgendwann einmal eine Fehlerquelle darstellen und muss dann behoben werden
                                         */

                                        $form->field($model_Dateianhang, 'attachement[]')->widget(FileInput::classname(), [
                                            'options' => ['multiple' => true],
                                            'pluginOptions' => ['allowedFileExtensions' => ['jpg', 'bmp', 'png', 'gif', 'docx', 'doc', 'xls', 'xlsx', 'csv', 'ppt', 'pptx', 'pdf', 'txt', 'avi', 'mpeg', 'mp3', 'ico']],
                                        ]);
                                        ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?=
                                        $form->field($model_Dateianhang, 'l_dateianhang_art_id')->widget(\kartik\widgets\Select2::classname(), [
                                            'data' => \yii\helpers\ArrayHelper::map(\frontend\models\LDateianhangArt::find()->orderBy('id')->asArray()->all(), 'id', 'bezeichnung'),
                                            'options' => ['placeholder' => Yii::t('app', 'Dateianhangsart')],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ])->label(false);
                                        ?>
                                    </div>
                                    <?php
                                    if ($model->isNewRecord) {
                                        ?>
                                        <div class="col-md-6">

                                            <?=
                                            $form->field($model_Dateianhang, 'angelegt_am', ['addon' => [
                                                    'prepend' => ['content' => 'angelegt am'], 'append' => ['content' => 'Diese Option übernimmt die Applikation']]])->widget(\kartik\datecontrol\DateControl::classname(), [
                                                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                                                'disabled' => true,
                                                'saveFormat' => 'php:Y-m-d H:i:s',
                                                'ajaxConversion' => true,
                                            ]);
                                            ?>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="col-md-6">

                                            <?=
                                            $form->field($model_Dateianhang, 'aktualisert_am', ['addon' => [
                                                    'prepend' => ['content' => 'aktualisert_am'], 'append' => ['content' => 'Diese Option übernimmt die Applikation']]])->widget(\kartik\datecontrol\DateControl::classname(), [
                                                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                                                'disabled' => true,
                                                'saveFormat' => 'php:Y-m-d H:i:s',
                                                'ajaxConversion' => true,
                                            ]);
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!--  Ending global CSS rules-->
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <?=
                        $form->field($model, 'bezeichnung', ['addon' => [
                                'prepend' => ['content' => 'Immobilien-Beschreibung']]])->widget(\dosamigos\ckeditor\CKEditor::className(), [
                            'preset' => 'full', 'clientOptions' => ['height' => 200],
                        ])
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?=
                        $form->field($model, 'sonstiges', ['addon' => [
                                'prepend' => ['content' => 'Sonstiges']]])->widget(\dosamigos\ckeditor\CKEditor::className(), [
                            'preset' => 'full', 'clientOptions' => ['height' => 200],
                        ])
                        ?>
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <?=
                        $form->field($model, 'stadt', ['addon' => [
                                'prepend' => ['content' => 'Stadt']]])->textInput(['maxlength' => true, 'placeholder' => 'Applikation füllt die Stadt gemäß der Postleitzahl', 'readOnly' => true, 'id' => 'immobilien-stadt'])
                        ?>

                    </div>
                    <div class="col-md-4">
                        <?=
                        $form->field($model, 'strasse', ['addon' => [
                                'prepend' => ['content' => 'Strasse']]])->textInput(['maxlength' => true, 'placeholder' => 'Bitte die Strasse und Hausnummer eingeben'])
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?=
                        $form->field($model, 'l_heizungsart_id', ['addon' => [
                                'prepend' => ['content' => 'Heizungsart']]])->widget(\kartik\widgets\Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map(\frontend\models\LHeizungsart::find()->orderBy('id')->asArray()->all(), 'id', 'bezeichnung'),
                            'options' => ['placeholder' => Yii::t('app', 'Bitte hier die Befeuerung eingeben')],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <b class="badge">Anzahl verfügbarer Zimmer</b>
                        <?=
                        $form->field($model, 'raeume')->widget(Slider::classname(), [
                            'pluginOptions' => [
                                'tooltip' => 'always',
                                'min' => 1,
                                'max' => 20,
                                'step' => 1
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-md-6">

                        <?=
                                $form->field($model, 'wohnflaeche', ['addon' => [
                                        'prepend' => ['content' => 'Wohnfläche']]])->widget(TwbsMaxlength::className())
                                ->textInput(['maxlength' => true, 'placeholder' => 'Bitte die maximale Nutzfläche']);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?=
                                $form->field($model, 'k_grundstuecksgroesse', ['addon' => [
                                        'prepend' => ['content' => 'Grundstücksgrösse']]])
                                ->textInput(['maxlength' => true, 'placeholder' => 'Bitte die maximale Grundstücksgrösse']);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?=
                                $form->field($model, 'k_provision', ['addon' => [
                                        'prepend' => ['content' => 'Provision'], 'append' => ['content' => 'in Prozent']]])
                                ->textInput(['maxlength' => true, 'placeholder' => 'Bitte den Provisionssatz eingeben']);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?=
                        $form->field($model, 'geldbetrag', ['addon' => [
                                'prepend' => ['content' => 'Kosten'], 'append' => ['content' => '€']]])->textInput(['placeholder' => 'Geben Sie hier ein, wieviel Geld Sie für die Immobilie wollen'])
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?=
                        $form->field($model, 'balkon_vorhanden')->widget(\kartik\checkbox\CheckboxX::classname(), [
                            'autoLabel' => true
                        ])->label(false);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?=
                        $form->field($model, 'fahrstuhl_vorhanden')->widget(\kartik\checkbox\CheckboxX::classname(), [
                            'autoLabel' => true
                        ])->label(false);
                        ?>
                    </div>
                    <div class="col-md-4">
                        <?=
                        $form->field($model, 'user_id', ['addon' => [
                                'prepend' => ['content' => 'Makler']]])->widget(\kartik\widgets\Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
                            'options' => ['placeholder' => Yii::t('app', 'Makler wählen')],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>
                    <!-- l_art_id wird als Parameter übergeben-->
                    <?php
                    if ($model->isNewRecord) {
                        ?>
                        <div class="col-md-4">
                            <?=
                            $form->field($model, 'angelegt_am', ['addon' => [
                                    'prepend' => ['content' => 'angelegt am'], 'append' => ['content' => 'Diese Option übernimmt die Applikation']]])->widget(\kartik\datecontrol\DateControl::classname(), [
                                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                                'disabled' => true,
                                'saveFormat' => 'php:Y-m-d H:i:s',
                                'ajaxConversion' => true,
                            ]);
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?=
                            $form->field($model, 'angelegt_von', ['addon' => [
                                    'prepend' => ['content' => 'angelegt von'], 'append' => ['content' => 'Diese Option übernimmt die Applikation']]])->widget(\kartik\widgets\Select2::classname(), [
                                'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
                                'disabled' => true,
                            ]);
                            ?>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="col-md-4">

                            <?=
                            $form->field($model, 'aktualisiert_von', ['addon' => [
                                    'prepend' => ['content' => 'aktualisert von'], 'append' => ['content' => 'Diese Option übernimmt die Applikation']]])->widget(\kartik\widgets\Select2::classname(), [
                                'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                                'options' => ['placeholder' => Yii::t('app', 'Choose User')],
                                'disabled' => true,
                            ]);
                            ?>
                        </div>
                        <div class="col-md-4">
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
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <?php if (Yii::$app->controller->action->id != 'save-as-new'): ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Erzeugen') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?php endif; ?>
    <?= Html::a(Yii::t('app', 'Abbruch'), ['/site/index'], ['class' => 'btn btn-danger']) ?>
</div>
<?php ActiveForm::end(); ?>

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

