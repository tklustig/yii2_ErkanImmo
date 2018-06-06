<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
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
    <!-- Beginn des Anhangformulars-->
    <!-- START ACCORDION & CAROUSEL-->
    <!--  Defining global CSS rules-->
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
                                </div><div class="col-md-12">
                                    <?=
                                    $form->field($model_Dateianhang, 'l_dateianhang_art_id')->widget(\kartik\widgets\Select2::classname(), [
                                        'data' => \yii\helpers\ArrayHelper::map(\frontend\models\LDateianhangArt::find()->orderBy('id')->asArray()->all(), 'id', 'bezeichnung'),
                                        'options' => ['placeholder' => Yii::t('app', 'Dateianhangsart')],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])->label(false);
                                    ?>
                                </div><div class="col-md-12">
                                    <?=
                                    $form->field($model_Dateianhang, 'angelegt_am')->widget(\kartik\datecontrol\DateControl::classname(), [
                                        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                                        'disabled' => true,
                                    ]);
                                    ?>
                                </div><div class="col-md-12">

                                    <?=
                                    $form->field($model_Dateianhang, 'angelegt_von', ['addon' => [
                                            'prepend' => ['content' => 'angelegt von']]])->widget(\kartik\widgets\Select2::classname(), [
                                        'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->asArray()->all(), 'id', 'username'),
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'disabled' => true,
                                        ],
                                    ])->label(false);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  Ending global CSS rules-->
                </div>
            </div>
        </div>
    </div>


    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'bezeichnung')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'strasse')->textInput(['maxlength' => true, 'placeholder' => 'Strasse']) ?>

    <?= $form->field($model, 'wohnflaeche')->textInput(['placeholder' => 'Wohnflaeche']) ?>

    <?= $form->field($model, 'raeume')->textInput(['placeholder' => 'Raeume']) ?>

    <?= $form->field($model, 'geldbetrag')->textInput(['maxlength' => true, 'placeholder' => 'Geldbetrag']) ?>

    <?= $form->field($model, 'l_plz_id')->textInput(['placeholder' => 'L Plz']) ?>

    <?=
    $form->field($model, 'l_stadt_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(frontend\models\LStadt::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose L stadt')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'user_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose User')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'l_art_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\frontend\models\LArt::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose L art')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'angelegt_am')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
        'saveFormat' => 'php:Y-m-d H:i:s',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Angelegt Am'),
                'autoclose' => true,
            ]
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'aktualisiert_am')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
        'saveFormat' => 'php:Y-m-d H:i:s',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Aktualisiert Am'),
                'autoclose' => true,
            ]
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'angelegt_von')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose User')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'aktualisiert_von')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose User')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
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
