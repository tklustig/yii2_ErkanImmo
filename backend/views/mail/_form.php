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
        <div class="col-md-12">

            <?=
            $form->field($modelDateianhang, 'attachement[]')->widget(FileInput::classname(), [
                'options' => ['multiple' => true],
                'pluginOptions' => ['maxFileSize' => 1024 * 1024 * 2, 'maxFileCount' => 10, 'allowedFileExtensions' => ['jpg', 'bmp', 'png', 'docx', 'doc', 'xls', 'xlsx', 'csv', 'ppt', 'pptx', 'pdf', 'txt', 'avi', 'mpeg', 'mp3', 'sql']
                    , 'initialCaption' => "Laden Sie hier beliebig viele Anhänge je 2 MB hoch...",
                ],
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'mail_from', ['addon' => [
                    'prepend' => ['content' => 'Mailabsender']]])->textInput(['maxlength' => true, 'value' => $mailFrom, 'readOnly' => true])
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
        <?= Html::a(Yii::t('app', 'Cancel'),['/mail/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
