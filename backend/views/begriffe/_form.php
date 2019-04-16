<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
?>

<div class="lbegriffe-form">
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
        <?= Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->referrer, ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
