<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
?>

<div class="mail-form">

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
        <div class="row">
            <div class="col-md-12">
                <?=
                /* 22.11.2017/tklustig/Initialisiert das Upload-Formular.Damit das multiple uploading klappt,muss die property als Array eingebunden werden
                  In Zeile 61 wird an eine statische URL zurück gerendert. Dass koennte irgendwann einmal eine Fehlerquelle darstellen und muss dann behoben werden
                 */

                $form->field($modelDateianhang, 'attachement[]')->widget(FileInput::classname(), [
                    'options' => ['multiple' => true],
                    'pluginOptions' => ['allowedFileExtensions' => ['jpg', 'bmp', 'png', 'gif', 'docx', 'doc', 'xls', 'xlsx', 'csv', 'ppt', 'pptx', 'pdf', 'txt', 'avi', 'mpeg', 'mp3', 'ico']],
                ]);
                ?>
            </div>
            <div class="col-md-6">
                <?=
                $form->field($modelDateianhang, 'l_dateianhang_art_id')->widget(\kartik\widgets\Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\frontend\models\LDateianhangArt::find()->orderBy('id')->where(['id' => [12, 13]])->asArray()->all(), 'id', 'bezeichnung'),
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
                    $form->field($modelDateianhang, 'angelegt_am', ['addon' => [
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
                    $form->field($modelDateianhang, 'aktualisert_am', ['addon' => [
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
        <div class="col-md-3">
            <?=
            $form->field($model, 'mail_from', ['addon' => [
                    'prepend' => ['content' => 'Mailabsender'],'append' => ['content' => 'readonly']]])->textInput(['maxlength' => true, 'value' => $mailFrom, 'readOnly' => true])
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'mail_to', ['addon' => [
                    'prepend' => ['content' => 'Mainempfänger']]])->textInput(['maxlength' => true, 'placeholder' => 'mult. Empfänger durch Semikolon trennen'])
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'mail_cc', ['addon' => [
                    'prepend' => ['content' => 'Cc Empfänger']]])->textInput(['maxlength' => true, 'placeholder' => 'mult. Empfänger durch Semikolon trennen'])
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'mail_bcc', ['addon' => [
                    'prepend' => ['content' => 'Bcc Empfänger']]])->textInput(['maxlength' => true, 'placeholder' => 'mult. Empfänger durch Semikolon trennen'])
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'betreff', ['addon' => [
                    'prepend' => ['content' => 'Betreff']]])->textInput(['maxlength' => true])
            ?>
        </div>
        <div class="col-md-6">

            <?=
            $form->field($model, 'id_mailserver', ['addon' => [
                    'prepend' => ['content' => 'Mailserver'], 'append' => ['content' => 'mit diesem Server wird die Mail verschickt']]])->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Mailserver::find()->orderBy('id')->asArray()->all(), 'id', 'serverHost'),
                'options' => ['placeholder' => Yii::t('app', 'Mailserver wählen')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-12">
            <?=
            $form->field($model, 'bodytext', ['addon' => [
                    'prepend' => ['content' => 'Mailinhalt']]])->widget(\dosamigos\ckeditor\CKEditor::className(), [
                'preset' => 'full', 'clientOptions' => ['height' => 400],
            ])
            ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['/mail/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
