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
        <div class="col-md-6">
            <span class="label label-success">Muliple Empfänger können durch<strong> Semikolon</strong> getrennt eingegeben werden</span>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'checkBoxDelete', ['addon' => [
                    'prepend' => ['content' => 'Entfernt physikalisch alle Anhänge nach dem Upload']]])->widget(\kartik\checkbox\CheckboxX::classname(), []);
            ?>
        </div>
        <div class="col-md-12">
            <?=
            /* 22.11.2017/tklustig/Initialisiert das Upload-Formular.Damit das multiple uploading klappt,muss die property als Array eingebunden werden
              In Zeile 61 wird an eine statische URL zurück gerendert. Dass koennte irgendwann einmal eine Fehlerquelle darstellen und muss dann behoben werden
             */

            $form->field($modelDateianhang, 'attachement[]')->widget(FileInput::classname(), [
                'options' => ['multiple' => true, 'placeholder' => Yii::t('app', 'Dateianhangsart')],
                'pluginOptions' => ['allowedFileExtensions' => ['jpg', 'jpeg', 'bmp', 'png', 'gif', 'psd', 'pcx', 'ico', 'docx', 'doc', 'xls', 'xlsx', 'csv', 'ppt', 'pptx', 'pdf', 'txt', 'avi', 'mpeg', 'mp3'],
                    'initialCaption' => "Hier ggf. Anhänge hochladen"],
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
        <div class="col-md-3">
            <?=
            $form->field($model, 'mail_from', ['addon' => [
                    'prepend' => ['content' => 'Mailabsender'], 'append' => ['content' => 'readonly']]])->textInput(['maxlength' => true, 'value' => $mailFrom, 'readOnly' => true])
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'mail_to', ['addon' => [
                    'prepend' => ['content' => 'Hauptempfänger']]])->textInput(['maxlength' => true])
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'mail_cc', ['addon' => [
                    'prepend' => ['content' => 'Cc Empfänger']]])->textInput(['maxlength' => true])
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'mail_bcc', ['addon' => [
                    'prepend' => ['content' => 'Bcc Empfänger']]])->textInput(['maxlength' => true])
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
        <div class="col-md-6">
            <?=
            $form->field($model, 'textbaustein_id', ['addon' => [
                    'prepend' => ['content' => 'Textbaustein'], 'append' => ['content' => 'wird in die Vorlage übernommen']]])->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(backend\models\LTextbaustein::find()->orderBy('id')->asArray()->all(), 'id', 'beschreibung'),
                'options' => ['placeholder' => Yii::t('app', 'Textbausteinbegriff wählen'),
                    'id' => 'bez'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'vorlage', ['addon' => [
                    'prepend' => ['content' => 'Vorlage']]])->textarea(['id' => 'IDText', 'rows' => 6, 'placeholder' => 'Kann per Copy&Paste in den Mailinhalt übernommen werden'])
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

    <?php
    ActiveForm::end();
    ?>

</div>
<?php
$script = <<< JS
    $('#bez').change(function(){
        var textId=$(this).val();
        var ausgabe='Der Textbaustein mit der Id:'+textId+' wird in die Voralge übernommen!';
        alert(ausgabe);
        $.get('mail/baustein',{textId:textId},function(data){
            $('#IDText').val(data);      
        });
    });
JS;
$this->registerJS($script);
?>
