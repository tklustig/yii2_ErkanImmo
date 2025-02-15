<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Impressumbegriffe');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lbegriffe-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'typ:html',
        'data:text',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update}',
            'buttons' => [
                'view' => function ( $id, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/begriffe/view', 'id' => $model->id], ['title' => 'View']);
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
            //'before' => Html::a(Yii::t('app', 'Begriffe erstellen'), ['/begriffe/create'], ['class' => 'btn btn-success', 'title' => 'Erstellt einen neuen Rechnungskopf']),
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['/begriffe/index'], ['class' => 'btn btn-warning', 'title' => 'Setzt die GridView zurück']),
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
