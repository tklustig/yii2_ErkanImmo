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
use common\modules\basis\models\Person;
use backend\modules\app_einstellung\models\Textbaustein;
use kartik\widgets\FileInput;
use kartik\dialog\Dialog;
use kartik\form\ActiveForm; /* lza 2017-10-12   Markup - Addon Prepended input, form umbennant */
use raoul2000\widget\twbsmaxlength\TwbsMaxlength;
use kartik\checkbox\CheckboxX;
?>
<?php
$values = array("sehr hoch" => "sehr hoch", "hoch" => "hoch", "mittel" => "mittel", "klein" => "klein", "sehr klein" => "sehr klein");
$bodyformat = array("text/html" => "text/html", "text/RichText" => "text/RichText", "text/plain" => "text/plain");
$betreff = "Stapelmail";
$url_man = "http://entwicklung.perswitch.de/img/mann.png";
$url_woman = "http://entwicklung.perswitch.de/img/frau.png";
$url_transi = "http://entwicklung.perswitch.de/img/gaim.png";
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
        <?=
        $form->field($model, 'attachement[]')->widget(FileInput::classname(), [
            'options' => ['multiple' => true],
            'pluginOptions' => ['maxFileSize' => 1024 * 1024 * 10, 'maxFileCount' => 10, 'allowedFileExtensions' => ['jpg', 'bmp', 'png', 'docx', 'doc', 'xls', 'xlsx', 'csv', 'ppt', 'pptx', 'pdf', 'txt', 'avi', 'mpeg', 'mp3', 'sql']
                , 'initialCaption' => "Laden Sie hier bis zu zehn Anhänge je 10 MB hoch...",
            ],
        ]);
        ?>
    </div>
    <div class="col-md-4">
        <?=
                $form->field($model, 'betreff', ['addon' => [
                        'prepend' => ['content' => 'Betreff']]])->widget(TwbsMaxlength::className())
                ->textInput(['value' => $betreff, 'maxlength' => true]);
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'wichtigkeit', ['addon' => [
                'prepend' => ['content' => 'Wichtigkeit']]])->widget(\kartik\widgets\Select2::classname(), [
            'data' => $values,
            'options' => ['placeholder' => Yii::t('app', '')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'bodyformat', ['addon' => [
                'prepend' => ['content' => 'Bodyformat']]])->widget(\kartik\widgets\Select2::classname(), [
            'data' => $bodyformat,
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($modelKontaktMail, 'mail', ['addon' => [
                'prepend' => ['content' => 'Quelladresse']]])->widget(\kartik\widgets\Select2::classname(), [
            'data' => \frontend\modules\kontakt\models\KontaktMail::getMailOfMitarbeiter(27, 28, 29, 30),
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'id_person_mitarbeiter', ['addon' => [
                'prepend' => ['content' => 'Mitarbeiter']]])->widget(\kartik\widgets\Select2::classname(), [
            'data' => Person::getPerson(3),
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    </div>
    <div class="col-md-12">
        <div class='box'>
            <?php
            if ($model::getBild($IdPerson) != NULL) {
                $Pic2Show = $model::getBild($IdPerson);
            } else {
                if ($geschlecht == 1) {
                    $Pic2Show = Html::img($url_man, ['alt' => 'Bewerberbild nicht vorhanden', 'class' => 'img-circle', 'style' => 'width:75px;height:75px']);
                } else if ($geschlecht == 2) {
                    $Pic2Show = Html::img($url_woman, ['alt' => 'Bewerberbild nicht vorhanden', 'class' => 'img-circle', 'style' => 'width:75px;height:75px']);
                } else if ($geschlecht == 3) {
                    $Pic2Show = Html::img($url_transi, ['alt' => 'Bewerberbild nicht vorhanden', 'class' => 'img-circle', 'style' => 'width:75px;height:75px']);
                }
            }
            ?>
            <div><label style='font-weight: lighter'><?= $name ?></label></div>
            <?= $Pic2Show ?>
            <label><font color='blue'><?= $Mailadress ?></font></label>
        </div></div>
    <div class="col-md-12"><h1></h1></div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'freigabe', ['showLabels' => true])->widget(CheckboxX::classname(), [
            'autoLabel' => true
        ])->label(false);
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'rememberMe', ['showLabels' => true])->widget(CheckboxX::classname(), [
            'autoLabel' => true
        ])->label(false);
        ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($modelTextbaustein, 'bezeichnung', ['addon' => [
                'prepend' => ['content' => 'Mailvorlagen']]])->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(Textbaustein::find()->orderBy('bezeichnung')->asArray()->all(), 'id', 'bezeichnung'),
            'options' => [
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
                $form->field($model, 'inhalt', ['addon' => [
                        'prepend' => ['content' => 'Textvorlage']]])->widget(TwbsMaxlength::className())
                ->textarea(['id' => 'IDText', 'rows' => 15]);
        ?>
    </div>
    <div class="col-md-12">
        <?=
        $form->field($model, 'bodytext')->widget(\dosamigos\ckeditor\CKEditor::className(), [
            'preset' => 'full', 'clientOptions' => ['height' => 200],
        ])
        ?>
    </div>

    <div class="form-group">
        <?php if (Yii::$app->controller->action->id != 'save-as-new'): ?>
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['/mail/mail-ausgang/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php
    ActiveForm::end();
    ?>

</div>
<?php
$script = <<< JS
    $('#bez').change(function(){
        var textId=$(this).val();
        $.get('textbaustein/baustein',{textId:textId},function(data){
          /*  var data=$.parseJSON(data); // This is not necessary any more... */
            $('#IDText').val(data);
        });
    });
JS;
$this->registerJS($script);
?>





