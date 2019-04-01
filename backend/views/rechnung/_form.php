<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use backend\models\Kopf;
use common\models\User;
use backend\models\LRechnungsart;
?>

<div class="rechnung-form">

    <?php
    $fk = Kopf::findOne(['user_id' => Yii::$app->user->identity->id])->user->username;
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
            <?=
            $form->field($model, 'beschreibung', ['addon' => [
                    'prepend' => ['content' => 'Zusatz'], 'append' => ['content' => 'Rumpf']]])->textarea(['id' => 'IDText', 'rows' => 6])
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'vorlage', ['addon' => [
                    'prepend' => ['content' => 'Vorlage'], 'append' => ['content' => 'Vorlagenart']]])->textarea(['id' => 'IDText_', 'rows' => 6])
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'kopf_id', ['addon' => [
                    'prepend' => ['content' => 'Rechnungsrumpf'], 'append' => ['content' => 'wird in den Zusatz übernommen']]])->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(Kopf::find()->orderBy('id')->asArray()->all(), 'id', 'user_id'),
                'options' => ['placeholder' => Yii::t('app', 'Rumpf wählen'),
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
            $form->field($model, 'rechungsart_id', ['addon' => [
                    'prepend' => ['content' => 'Vorlagenart'], 'append' => ['content' => 'wird in die Vorlage übernommen']]])->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(LRechnungsart::find()->orderBy('id')->asArray()->all(), 'id', 'art'),
                'options' => ['placeholder' => Yii::t('app', 'Art wählen'),
                        'id' => 'bez_'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-12">
            <?=
            $form->field($model, 'rechnungPlain', ['addon' => [
                    'prepend' => ['content' => 'eigentliche Rechnung']]])->widget(\dosamigos\ckeditor\CKEditor::className(), [
                'preset' => 'full', 'clientOptions' => ['height' => 400],
            ])
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'datumerstellung', ['addon' => [
                    'prepend' => ['content' => 'Rechnungsdatum']]])->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                'saveFormat' => 'php:Y-m-d',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Datum wählen'),
                        'autoclose' => true
                    ]
                ],
            ]);
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'datumfaellig', ['addon' => [
                    'prepend' => ['content' => 'Fälligkeit']]])->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                'saveFormat' => 'php:Y-m-d',
                'ajaxConversion' => true,
                'options' => [
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Choose Datumfaellig'),
                        'autoclose' => true
                    ]
                ],
            ]);
            ?>
        </div>
        <div class="col-md-4">       
            <?=
            $form->field($model, 'geldbetrag', ['addon' => [
                    'prepend' => ['content' => 'Geldbetrag']]])->textInput(['maxlength' => true, 'placeholder' => 'Geldbetrag'])
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'mwst_id', ['addon' => [
                    'prepend' => ['content' => 'MwSt-Satz']]])->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\LMwst::find()->orderBy('id')->asArray()->all(), 'id', 'satz'),
                'options' => ['placeholder' => Yii::t('app', 'MwSt wählen(in %)')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'kunde_id', ['addon' => [
                    'prepend' => ['content' => 'für Kunde']]])->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(frontend\models\Kunde::find()->orderBy('id')->asArray()->all(), 'id', 'nachname'),
                'options' => ['placeholder' => Yii::t('app', 'Kunde wählen')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'makler_id', ['addon' => [
                    'prepend' => ['content' => 'von Makler']]])->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
                'options' => ['placeholder' => Yii::t('app', 'Mkaler wählen')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <?php if (Yii::$app->controller->action->id != 'save-as-new'): ?>
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
        <?php if (Yii::$app->controller->action->id != 'create'): ?>
            <?= Html::submitButton(Yii::t('app', 'Save As New'), ['class' => 'btn btn-info', 'value' => '1', 'name' => '_asnew']) ?>
        <?php endif; ?>
        <?= Html::a(Yii::t('app', 'Cancel'), ['/site/index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$data = Kopf::findOne(['user_id' => Yii::$app->user->identity->id])->data;
$ersetzenMitName = User::findOne(['id' => Yii::$app->user->identity->id])->username;
$ersetzenMitMail = User::findOne(['id' => Yii::$app->user->identity->id])->email;
$ersetzenMitTelefon = User::findOne(['id' => Yii::$app->user->identity->id])->telefon;
$replaceMaklerName = str_replace('****', $ersetzenMitName, $data);
$replaceMaklerName = str_replace('++++', $ersetzenMitTelefon, $replaceMaklerName);
$replaceMaklerName = str_replace('::::', $ersetzenMitMail, $replaceMaklerName);
$finalOutput = preg_replace("#[\r\n]#", '', $replaceMaklerName);
$script = <<< JS
    $('#bez').change(function(){
        var finalData = '<?php echo $finalOutput; ?>'; 
        var textId=$(this).val();
        var ausgabe='Die Tabellendaten werden auschliesslich für den aktuell angemeldeten Makler ersetzt. Um Mißbrauch vorzubeugen, unterstützt die Applikation nicht Rechnungsköpfe anderer Makler';
        alert(ausgabe);
        $.get('kopf/baustein',{textId:textId},function(data){
            var result = data.replace(data, finalData);
            result=result.substr(11);
            result=result.substr(0, result.length-5);
            $('#IDText').val(result);      
        });
    });
JS;
$this->registerJS($script);
?>
<?php
$script_ = <<< JS
    $('#bez_').change(function(){
        var textId=$(this).val();
        alert(textId);
        $.get('rechnungsart/baustein',{textId:textId},function(data){
            $('#IDText_').val(data);      
        });
    });
JS;
$this->registerJS($script_);
?>