<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;

$form = ActiveForm::begin([
            'id' => 'dynamic-form',
            'type' => ActiveForm::TYPE_VERTICAL,
            'formConfig' => [
                'showLabels' => false
        ]]);
?>
<?= $form->errorSummary($model); ?>
<?php
if ($model->isNewRecord) {
    $this->title = Yii::t('app', 'Bilder hochladen');
} else {
    $this->title = Yii::t('app', 'Aktualisiere {modelClass}: ', [
                'modelClass' => 'Bilder',
            ]) . ' ' . $model->id;
}
$max = frontend\models\base\LDateianhangArt::find()->max('id');
?>
<center><h1><?= Html::encode($this->title) ?></h1></center>
<div class="row">
    <div class="col-md-12">
        <?=
        /* 22.11.2017/tklustig/Initialisiert das Upload-Formular.Damit das multiple uploading klappt,muss die property als Array eingebunden werden
          In Zeile 61 wird an eine statische URL zurück gerendert. Dass koennte irgendwann einmal eine Fehlerquelle darstellen und muss dann behoben werden
         */

        $form->field($model, 'attachement[]')->widget(FileInput::classname(), [
            'options' => ['multiple' => true],
            'pluginOptions' => ['allowedFileExtensions' => ['jpg','jpeg', 'bmp', 'png', 'gif', 'docx', 'doc', 'xls', 'xlsx', 'csv', 'ppt', 'pptx', 'pdf', 'txt', 'avi', 'mpeg', 'mp3', 'ico']],
        ]);
        ?>
    </div>
    <div class="col-md-12">
        <?=
        $form->field($model, 'l_dateianhang_art_id')->widget(\kartik\widgets\Select2::classname(), [
            'data' => \yii\helpers\ArrayHelper::map(\frontend\models\LDateianhangArt::find()->where(['id' => [10,11]])->asArray()->all(), 'id', 'bezeichnung'),
            'options' => ['placeholder' => Yii::t('app', 'Dateianhangsart')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    </div>
    <?php
    if ($model->isNewRecord) {
        ?>
        <div class="col-md-6">

            <?=
            $form->field($model, 'angelegt_am', ['addon' => [
                    'prepend' => ['content' => 'angelegt am'], 'append' => ['content' => 'Diese Option übernimmt die Applikation']]])->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                'disabled' => true,
                'saveFormat' => 'php:Y-m-d H:i:s',
                'ajaxConversion' => true,
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'angelegt_von', ['addon' => [
                    'prepend' => ['content' => 'angelegt von'], 'append' => ['content' => 'Diese Option übernimmt die Applikation']]])->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
                'disabled' => true,
                'id' => 'id_X'
            ]);
            ?>
        </div>
        <?php
    } else {
        ?>
        <div class="col-md-6">

            <?=
            $form->field($model, 'aktualisiert_am', ['addon' => [
                    'prepend' => ['content' => 'aktualisiert am']]])->widget(\kartik\datecontrol\DateControl::classname(), [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                'saveFormat' => 'php:Y-m-d H:i:s',
                'ajaxConversion' => true,
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'aktualisiert_von', ['addon' => [
                    'prepend' => ['content' => 'aktualisiert von']]])->widget(\kartik\widgets\Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(common\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'username'),
                'id' => 'id_X'
            ]);
            ?>
        </div>
        <?php
    }
    ?>
</div>
<div class="form-group">
    <?php if (Yii::$app->controller->action->id != 'save-as-new'): ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Erzeugen') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?php endif; ?>
    <?= Html::a(Yii::t('app', 'Abbruch'), ['/site/index'], ['class' => 'btn btn-danger']) ?>
</div>
<?php ActiveForm::end(); ?>

