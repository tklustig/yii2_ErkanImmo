<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use kartik\slider\Slider;
?>

<div class="immobilien-form">

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
        $this->title = Yii::t('app', 'Create Immobilien');
    } else {
        $this->title = Yii::t('app', 'Update {modelClass}: ', [
                    'modelClass' => 'Immobilien',
                ]) . ' ' . $model->id;
    }
    ?>
    <br><br><h1><?= Html::encode($this->title) ?></h1>
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
                                    <div class="col-md-12">
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
                                        <div class="col-md-12">

                                            <?=
                                            $form->field($model_Dateianhang, 'angelegt_am', ['addon' => [
                                                    'prepend' => ['content' => 'angelegt am'], 'append' => ['content' => 'Diese Option übernimmt die Applikation']]])->widget(\kartik\datecontrol\DateControl::classname(), [
                                                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                                                'disabled' => true,
                                                'saveFormat' => 'php:Y-m-d H:i:s',
                                                'ajaxConversion' => true,
                                            ]);
                                            ?>
                                        </div><div class="col-md-12">


                                            <?=
                                            $form->field($model_Dateianhang, 'angelegt_von', ['addon' => [
                                                    'prepend' => ['content' => 'angelegt von']]])->widget(\kartik\widgets\Select2::classname(), [
                                                'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ]);
                                            ?>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="col-md-12">

                                            <?=
                                            $form->field($model_Dateianhang, 'aktualisiert_von', ['addon' => [
                                                    'prepend' => ['content' => 'aktualisiert von']]])->widget(\kartik\widgets\Select2::classname(), [
                                                'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                                                'options' => ['placeholder' => Yii::t('app', 'Choose User')],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ]);
                                            ?>
                                        </div>
                                        <div class="col-md-12">

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
                    <div class="col-md-12">
                        <?=
                        $form->field($model, 'bezeichnung')->widget(\dosamigos\ckeditor\CKEditor::className(), [
                            'preset' => 'full', 'clientOptions' => ['height' => 200],
                        ])
                        ?>
                    </div> <div class="col-md-12">

                        <?=
                        $form->field($model, 'strasse', ['addon' => [
                                'prepend' => ['content' => 'Strasse']]])->textInput(['maxlength' => true, 'placeholder' => 'Bitte die Strasse eingeben'])
                        ?>
                    </div> <div class="col-md-12">
                        <b class="badge">Wohnfläche</b>
                        <?=
                        '<b class="badge">in Quadratmeter</b>' .
                        $form->field($model, 'wohnflaeche')->widget(Slider::classname(), [
                            'sliderColor' => Slider::TYPE_GREY,
                            'handleColor' => Slider::TYPE_DANGER,
                            'pluginOptions' => [
                                'handle' => 'triangle',
                                'tooltip' => 'always',
                                'min' => 20,
                                'max' => 600,
                                'step' => 10
                            ]
                        ]);
                        ?>
                    </div> <div class="col-md-12">

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
                    </div> <div class="col-md-12">

                        <?=
                        $form->field($model, 'geldbetrag', ['addon' => [
                                'prepend' => ['content' => 'Kosten']]])->textInput(['placeholder' => 'Geben Sie hier ein, wieviel Geld Sie für die Immobilie wollen'])
                        ?>
                    </div> <div class="col-md-12">

                        <?=
                        $form->field($model, 'l_plz_id', ['addon' => [
                                'prepend' => ['content' => 'PLZ']]])->widget(\kartik\widgets\Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map(frontend\models\LPlz::find()->orderBy('id')->asArray()->all(), 'id', 'plz'),
                            'options' => ['placeholder' => Yii::t('app', 'PLZ auswählen')],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div> <div class="col-md-12">

                        <?=
                        $form->field($model, 'l_stadt_id', ['addon' => [
                                'prepend' => ['content' => 'Stadt']]])->widget(\kartik\widgets\Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map(frontend\models\LStadt::find()->orderBy('id')->asArray()->all(), 'id', 'stadt'),
                            'options' => ['placeholder' => Yii::t('app', 'Stadt wählen')],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div> <div class="col-md-12">

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
                    </div> <div class="col-md-12">

                        <?=
                        $form->field($model, 'l_art_id', ['addon' => [
                                'prepend' => ['content' => 'Typ']]])->widget(\kartik\widgets\Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map(\frontend\models\LArt::find()->orderBy('id')->asArray()->all(), 'id', 'bezeichnung'),
                            'options' => ['placeholder' => Yii::t('app', 'Art des Objektes wählen')],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>
                    <?php
                    if ($model->isNewRecord) {
                        ?>
                        <div class="col-md-12">

                            <?=
                            $form->field($model, 'angelegt_am', ['addon' => [
                                    'prepend' => ['content' => 'angelegt am'], 'append' => ['content' => 'Diese Option übernimmt die Applikation']]])->widget(\kartik\datecontrol\DateControl::classname(), [
                                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                                'disabled' => true,
                                'saveFormat' => 'php:Y-m-d H:i:s',
                                'ajaxConversion' => true,
                            ]);
                            ?>
                        </div><div class="col-md-12">


                            <?=
                            $form->field($model, 'angelegt_von', ['addon' => [
                                    'prepend' => ['content' => 'angelegt von']]])->widget(\kartik\widgets\Select2::classname(), [
                                'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="col-md-12">

                            <?=
                            $form->field($model, 'aktualisiert_von', ['addon' => [
                                    'prepend' => ['content' => 'aktualisert von']]])->widget(\kartik\widgets\Select2::classname(), [
                                'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                                'options' => ['placeholder' => Yii::t('app', 'Choose User')],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="col-md-12">

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
    <div class="form-group">
        <?php if (Yii::$app->controller->action->id != 'save-as-new'): ?>
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
        <?php if (Yii::$app->controller->action->id != 'create'): ?>
            <?= Html::submitButton(Yii::t('app', 'Save As New'), ['class' => 'btn btn-info', 'value' => '1', 'name' => '_asnew']) ?>
        <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->referrer, ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

