<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
?>

<div class="rechnung-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'formConfig' => [
                    'showLabels' => true
                ]
    ]);
    ?>
    <?= $form->errorSummary($model); ?>
    <div class="row">
        <div class="col-md-12">
            <?=
            $form->field($model, 'beschreibung', ['addon' => [
                    'prepend' => ['content' => 'Beschreibung'], 'append' => ['content' => 'Kopf']]])->textarea(['rows' => 6])
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'kopf_id', ['addon' => [
                    'prepend' => ['content' => 'Rechnungskopf'], 'append' => ['content' => 'wird in die Beschreibung übernommen']]])->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\Kopf::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                'options' => ['placeholder' => Yii::t('app', 'Choose Kopf')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
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
                'data' => \yii\helpers\ArrayHelper::map(\backend\models\LMwst::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                'options' => ['placeholder' => Yii::t('app', 'MwSt wählen')],
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
                'data' => \yii\helpers\ArrayHelper::map(frontend\models\Kunde::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
                'options' => ['placeholder' => Yii::t('app', 'Kunde wählen')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-6">
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
        <?= Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->referrer, ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>