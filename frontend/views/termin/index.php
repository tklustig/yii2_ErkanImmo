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
        <?php
        if (!empty($abgelaufenMessage))
            echo '<font color="red">' . $abgelaufenMessage . '</font>';
        ?>
    </center>
    <?php Pjax::begin(); ?>
    <?php
    $link = \Yii::$app->urlManagerBackend->baseUrl . '/home';
    $dummy = 'id';
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
        'id',
        [
            'attribute' => 'id',
            'label' => Yii::t('app', 'Immobilien-Id'),
            'value' => function($model) {
                return $model->immobilien->id;
            }
        ],
        [
            'attribute' => $dummy,
            'label' => Yii::t('app', 'mit Makler'),
            'value' => function($model, $id) {
                $maklerId = frontend\models\Adminbesichtigungkunde::findOne(['besichtigungstermin_id' => $id])->admin_id;
                $maklerName = common\models\User::findOne(['id' => $maklerId])->username;
                return $maklerName;
            }
        ],
        'immobilien.stadt',
        [
            'attribute' => $dummy,
            'label' => Yii::t('app', 'Art'),
            'value' => function($model) {
                if ($model->immobilien->l_art_id == 1)
                    $begriff = "Vermietobjekt";
                else if ($model->immobilien->l_art_id == 2)
                    $begriff = "Verkaufsobjekt";
                return $begriff;
            }
        ],
        [
            'attribute' => 'uhrzeit',
            'label' => Yii::t('app', 'DateTime'),
            'value' => function($model) {
                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $model->uhrzeit);
                return $dateTime->format('d-m-Y H:i:s');
            }
        ],
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
            'template' => '{kunde}<br>{kundenmap} {immomap}<br>{update} {delete}',
            'buttons' => [
                'kunde' => function ( $id, $model) {
                    $sessionPHP = Yii::$app->session;
                    $sessionPHP->open();
                    $header = $sessionPHP['header'];
                    $sessionPHP->close();
                    return Html::a('<span class="glyphicon glyphicon-home"></span>', ['/termin/link', 'id' => $model->id, 'header' => $header], ['title' => 'Interessent anzeigen', 'data' => ['pjax' => '0']]);
                },
                'kundenmap' => function ( $id, $model) {
                    return Html::a('<span class="glyphicon glyphicon-download"></span>', ['/termin/map', 'id' => $model->id], ['title' => 'Treffpunkt in Karte anzeigen. Blendet danach alle Termine ein', 'data' => ['pjax' => '0']]);
                },
                'immomap' => function ( $id, $model) {
                    return Html::a('<span class="glyphicon glyphicon-upload"></span>', ['/termin/googlemap', 'id' => $model->id], ['title' => 'Immobilie in Karte anzeigen. Blendet danach alle Termine ein', 'data' => ['pjax' => '0']]);
                },
            /* 'update' => function ( $id, $model) {
              return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/termin/update', 'id' => $model->id], ['title' => 'Bearbeiten']);
              }, */
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
