<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Kunde */
/* @var $form yii\widgets\ActiveForm */

\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'Adminbesichtigungkunde', 
        'relID' => 'adminbesichtigungkunde', 
        'value' => \yii\helpers\Json::encode($model->adminbesichtigungkundes),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'EDateianhang', 
        'relID' => 'edateianhang', 
        'value' => \yii\helpers\Json::encode($model->eDateianhangs),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
\mootensai\components\JsBlock::widget(['viewFile' => '_script', 'pos'=> \yii\web\View::POS_END, 
    'viewParams' => [
        'class' => 'Kundeimmobillie', 
        'relID' => 'kundeimmobillie', 
        'value' => \yii\helpers\Json::encode($model->kundeimmobillies),
        'isNewRecord' => ($model->isNewRecord) ? 1 : 0
    ]
]);
?>

<div class="kunde-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'id', ['template' => '{input}'])->textInput(['style' => 'display:none']); ?>

    <?= $form->field($model, 'l_plz_id')->textInput(['placeholder' => 'L Plz']) ?>

    <?= $form->field($model, 'geschlecht')->textInput(['maxlength' => true, 'placeholder' => 'Geschlecht']) ?>

    <?= $form->field($model, 'vorname')->textInput(['maxlength' => true, 'placeholder' => 'Vorname']) ?>

    <?= $form->field($model, 'nachname')->textInput(['maxlength' => true, 'placeholder' => 'Nachname']) ?>

    <?= $form->field($model, 'stadt')->textInput(['maxlength' => true, 'placeholder' => 'Stadt']) ?>

    <?= $form->field($model, 'strasse')->textInput(['maxlength' => true, 'placeholder' => 'Strasse']) ?>

    <?= $form->field($model, 'geburtsdatum')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
        'saveFormat' => 'php:Y-m-d',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Geburtsdatum'),
                'autoclose' => true
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'solvenz')->checkbox() ?>

    <?= $form->field($model, 'bankverbindung_id')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\frontend\models\Bankverbindung::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose Bankverbindung')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'angelegt_am')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
        'saveFormat' => 'php:Y-m-d H:i:s',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Angelegt Am'),
                'autoclose' => true,
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'aktualisiert_am')->widget(\kartik\datecontrol\DateControl::classname(), [
        'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
        'saveFormat' => 'php:Y-m-d H:i:s',
        'ajaxConversion' => true,
        'options' => [
            'pluginOptions' => [
                'placeholder' => Yii::t('app', 'Choose Aktualisiert Am'),
                'autoclose' => true,
            ]
        ],
    ]); ?>

    <?= $form->field($model, 'angelegt_von')->textInput(['placeholder' => 'Angelegt Von']) ?>

    <?= $form->field($model, 'aktualisiert_von')->widget(\kartik\widgets\Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\frontend\models\User::find()->orderBy('id')->asArray()->all(), 'id', 'id'),
        'options' => ['placeholder' => Yii::t('app', 'Choose User')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?php
    $forms = [
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode(Yii::t('app', 'Adminbesichtigungkunde')),
            'content' => $this->render('_formAdminbesichtigungkunde', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->adminbesichtigungkundes),
            ]),
        ],
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode(Yii::t('app', 'EDateianhang')),
            'content' => $this->render('_formEDateianhang', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->eDateianhangs),
            ]),
        ],
        [
            'label' => '<i class="glyphicon glyphicon-book"></i> ' . Html::encode(Yii::t('app', 'Kundeimmobillie')),
            'content' => $this->render('_formKundeimmobillie', [
                'row' => \yii\helpers\ArrayHelper::toArray($model->kundeimmobillies),
            ]),
        ],
    ];
    echo kartik\tabs\TabsX::widget([
        'items' => $forms,
        'position' => kartik\tabs\TabsX::POS_ABOVE,
        'encodeLabels' => false,
        'pluginOptions' => [
            'bordered' => true,
            'sideways' => true,
            'enableCache' => false,
        ],
    ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Cancel'), Yii::$app->request->referrer , ['class'=> 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
