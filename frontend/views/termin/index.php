<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\TerminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Besichtigungstermin(e)');

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="besichtigungstermin-index">
    <center>
        <h1><?= Html::encode($this->title) ?></h1>
    </center>
    <?php Pjax::begin(); ?>
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
            }
        ],
        [
            'attribute' => 'id',
            'contentOptions' => ['class' => 'id_breite_css'],
        ],
        'uhrzeit',
        'Relevanz',
        'immobilien.stadt'
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
                '{export}',
                '{toggleData}'
            ],
            'toggleDataOptions' => ['minCount' => 10],
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>
