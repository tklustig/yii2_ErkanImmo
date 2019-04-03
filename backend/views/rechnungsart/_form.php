<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
?>

<div class="lrechnungsart-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'type' => ActiveForm::TYPE_VERTICAL,
                'formConfig' => [
                    'showLabels' => false
                ]
    ]);
    ?>
    <div class="row">

        <?= $form->errorSummary($model); ?>
        <div class="col-md-12">
            <?=
            $form->field($model, 'art', ['addon' => [
                    'prepend' => ['content' => 'Art'], 'append' => ['content' => 'Verkauf oder Vermietung']]])->textInput(['maxlength' => true, 'placeholder' => 'Art'])
            ?>
        </div>
        <div class="col-md-12">
            <?=
            $form->field($model, 'data', ['addon' => [
                    'prepend' => ['content' => 'Inhalt']]])->widget(\dosamigos\ckeditor\CKEditor::className(), [
                'preset' => 'full', 'clientOptions' => ['height' => 400],
            ])
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
