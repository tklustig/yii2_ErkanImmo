<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Firma');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firma-index">

    <h1><?= Html::encode($this->title) ?></h1>
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
        'firmenname',
        [
            'attribute' => 'l_rechtsform_id',
            'label' => Yii::t('app', 'Rechtsform'),
            'value' => function($model) {
                return $model->lRechtsform->typus;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\backend\models\LRechtsform::find()->asArray()->all(), 'id', 'typus'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Rechtsform', 'id' => 'grid-firma-search-l_rechtsform_id']
        ],
        'strasse',
        'hausnummer',
        'ort',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{save-as-new} {view} {update} {delete}',
            'buttons' => [
                'save-as-new' => function ($url) {
                    return Html::a('<span class="glyphicon glyphicon-copy"></span>', $url, ['title' => 'Save As New']);
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
            'before' => Html::a(Yii::t('app', 'Firmendaten erstellen'), ['/firma/create'], ['class' => 'btn btn-success', 'title' => 'Erstellt  neue Firmendaten']),
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['/firma/index'], ['class' => 'btn btn-warning', 'title' => 'Setzt die GridView zurück']),
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
