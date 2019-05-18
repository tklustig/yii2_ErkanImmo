<style>
    .box {
        float: left;
        width: 100%;
        margin-right: 1%;
        background: #eee;
        border: 1px solid;
        padding: 10px;
        box-shadow: 5px 10px #888888;
    }

</style>
<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
?>

<div class="mail-ausgang-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'formConfig' => [
                    'showLabels' => false
    ]]);
    ?>
    <div class="col-md-12">
        <div class='box'>
            <?php
            if ($model::getBild($id) != NULL) {
                $pic = $model::getBild($id);
                $Pic2Show = Html::img($pic, ['alt' => 'Kundenbild nicht vorhanden', 'class' => 'img-circle', 'style' => 'width:75px;height:75px']);
            } else {
                ?><label>Kundenbild nicht hinterlegt</label><?php
            }
            ?>
            <div><label style='font-weight: lighter'><?= $geschlecht . ' ' ?><?= $name ?></label></div>
            <?= $Pic2Show ?>
            <label><font color='blue'><?= $Mailadress ?></font></label>
        </div></div>
    <div class="col-md-12"><h1></h1></div>
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
            'data' => \yii\helpers\ArrayHelper::map(\frontend\models\LDateianhangArt::find()->orderBy('id')->where(['id' => [12, 13, 15]])->asArray()->all(), 'id', 'bezeichnung'),
            'options' => ['placeholder' => Yii::t('app', 'Dateianhangsart')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label(false);
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'mail_from', ['addon' => [
                'prepend' => ['content' => 'Mailabsender'], 'append' => ['content' => 'readonly']]])->textInput(['maxlength' => true, 'value' => $mailFrom, 'readOnly' => true])
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

    <div class="form-group">
        <?php if (Yii::$app->controller->action->id != 'save-as-new'): ?>
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Send') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['/kunde/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php
    ActiveForm::end();
    ?>

</div>
<?php
$script = <<< JS
    $('#bez').change(function(){
        var textId=$(this).val();
        var ausgabe='Der Textbaustein mit der Id:'+textId+' wird in die Vorlage übernommen!';
        alert(ausgabe);
        $.get('mail/baustein',{textId:textId},function(data){
            $('#IDText').val(data);      
        });
    });
JS;
$this->registerJS($script);
?>






