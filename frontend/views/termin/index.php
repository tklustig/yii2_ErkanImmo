<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

$sessionPHP = Yii::$app->session;
if (!empty($header)) {
    $this->title = Yii::t('app', $header);
    $sessionPHP->open();
    $sessionPHP['header'] = $header;
    $sessionPHP->close();
} else {
    $this->title = Yii::t('app', 'Alle Besichtigungstermine anzeigen');
    $sessionPHP->destroy();
}

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="besichtigungstermin-index">
    <center>
        <h1><?= Html::encode($this->title) ?></h1>
    </center>
    <?php Pjax::begin(); ?>
    <?php
    $link = \Yii::$app->urlManagerBackend->baseUrl . '/home';
    $showLink = false;
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
            }
        ],
        [
            'attribute' => 'id',
            'label' => Yii::t('app', 'Immobilien-Id'),
            'value' => function($model) {
                return $model->immobilien->id;
            }
        ],
        'immobilien.stadt',
        [
            'attribute' => '',
            'label' => Yii::t('app', 'Art'),
            'value' => function($model) {
                if ($model->immobilien->l_art_id == 1)
                    $begriff = "Vermietobjekt";
                else if ($model->immobilien->l_art_id == 2)
                    $begriff = "Verkaufsobjekt";
                return $begriff;
            }
        ],
        'uhrzeit',
        [
            'class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'Relevanz',
            'trueLabel' => 'Ja',
            'falseLabel' => 'Nein',
            'label' => 'Priorität hoch',
            'encodeLabel' => false,
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{kunde}<br>{delete}<br>{update}',
            'buttons' => [
                'kunde' => function ($model, $id) {
                    $sessionPHP = Yii::$app->session;
                    $sessionPHP->open();
                    $header = $sessionPHP['header'];
                    $sessionPHP->close();
                    return Html::a('<span class="glyphicon glyphicon-home"></span>', ['/termin/link', 'id' => $id->id, 'header' => $header], ['title' => 'Interessent anzeigen', 'data' => ['pjax' => '0']]);
                },
                'update' => function ($model, $id) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/termin/update', 'id' => $id->id], ['title' => 'Bearbeiten']);
                },
            ],
        ],
    ];
    ?>
    <div class="container-fluid">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumn,
            'pjax' => true,
            'pjaxSettings' => [
                'neverTimeout' => true,
            ],
            'options' => [
                'style' => 'overflow: auto; word-wrap: break-word;'
            ],
            'condensed' => true,
            'responsiveWrap' => true,
            'hover' => true,
            'persistResize' => true,
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                "heading" => "<h3 class='panel-title'><i class='glyphicon glyphicon-globe'></i> " . $this->title . "</h3>",
                'toggleDataOptions' => ['minCount' => 10],
            ],
            'toolbar' => [
                ['content' =>
                    Html::a('<span class=" fa fa-envelope-square"> zurück', $link, ['class' => 'btn btn-default', 'title' => 'rendert zum Backend zurück', 'data' => ['pjax' => '0']])
                ],
                '{export}',
                '{toggleData}'
            ],
            'toggleDataOptions' => ['minCount' => 10],
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>
