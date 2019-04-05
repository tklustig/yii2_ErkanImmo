<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\form\ActiveForm;

$this->title = Yii::t('app', 'Mail');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="mail-index">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($searchModel, 'choice_date')->radioList([0 => 'Vorher', 1 => 'Nachher'], ['itemOptions' => ['class' => 'choiceRadio']])->label('Grenzen Sie über diese beiden Radio Buttons Ihre Suche in AdvancedSearch ein'); ?>
    <?php
    ActiveForm::end();
    ?>
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button']) ?>
    </p>
    <div class="search-form" style="display:none">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'width' => '50px',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true
        ],
        'id',
        'mail_from',
        'mail_to',
        'mail_cc',
        'mail_bcc',
        'betreff',
        'bodytext:html',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{save-as-new} {view} {update} {delete}',
            'buttons' => [
                'view' => function ($id, $model) {
                    return Html::a('<span class="glyphicon glyphicon-copy"></span>', ['/mail/view', 'id' => $model->id], ['title' => 'Anzeigen']);
                },
            ],
        ],
    ];
    ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterSelector' => '.choiceRadio',
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
        'options' => [
        //'style' => 'overflow: auto; word-wrap: break-word;'
        ],
        'condensed' => true,
        'responsiveWrap' => true,
        'hover' => true,
        'persistResize' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            "heading" => "<h3 class='panel-title'><i class='glyphicon glyphicon-globe'></i> " . $this->title . "</h3>",
            'before' => Html::a(Yii::t('app', 'Mail erstellen'), ['/mail/create'], ['class' => 'btn btn-success', 'title' => 'Erstellt eine neue Mail']),
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['/mail/index'], ['class' => 'btn btn-warning', 'title' => 'Setzt die GridView zurück']),
            'toggleDataOptions' => ['minCount' => 10],
        ],
        'toolbar' => [
            '{export}',
            '{toggleData}'
        ],
        'toggleDataOptions' => ['minCount' => 10],
    ]);
    ?>

</div>
