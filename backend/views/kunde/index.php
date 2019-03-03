<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Kunde');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="kunde-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);   ?>

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
        ['attribute' => 'id', 'visible' => false],
        'lPlz.plz',
        'geschlecht',
        'vorname',
        'nachname',
        'stadt',
        'strasse',
        [
            'attribute' => 'geburtsdatum',
            'format' => 'html',
            'label' => Yii::t('app', 'Geburtsdatum'),
            'value' => function($model, $id) {
                if ($model->geburtsdatum) {
                    $expression = new yii\db\Expression('NOW()');
                    $now = (new \yii\db\Query)->select($expression)->scalar();
                    $diff = strtotime($now) - strtotime($model->geburtsdatum);
                    $hours = floor($diff / (60 * 60));
                    $year = floor($hours / 24 / 365);
                    $output = date("d.m.Y", strtotime($model->geburtsdatum)) . '<br>' . $year . " Jahre alt";
                    return $output;
                } else {
                    return NULL;
                }
            },
        ],
        [
            'class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'solvenz',
            'trueLabel' => 'Ja',
            'falseLabel' => 'Nein',
            'label' => 'Ist Solvent',
            'encodeLabel' => false,
        ],
        [
            'attribute' => 'bankverbindung_id',
            'label' => Yii::t('app', 'BankverbindungID'),
            'value' => function($model) {
                if ($model->bankverbindung) {
                    return $model->bankverbindung->id;
                } else {
                    return NULL;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\backend\models\Bankverbindung::find()->asArray()->all(), 'id', 'id'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Bankverbindung', 'id' => 'grid-kunde-search-bankverbindung_id']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{bankverbindung},{termin}',
            'buttons' => [
                'bankverbindung' => function ($id, $model) {
                    if (!empty($model->bankverbindung_id)) {
                        $pk = backend\models\Bankverbindung::findOne(['id' => $model->bankverbindung_id])->id;
                        return Html::a('<span class="glyphicon glyphicon-th-list"></span>', ['/bankverbindung/view', 'id' => $pk], ['title' => 'Bankverbindung anzeigen']);
                    }
                },
                'termin' => function ($id, $model) {
                    $data = frontend\models\Adminbesichtigungkunde::find()->all();
                    foreach ($data as $item) {
                        if ($item->kunde_id == $model->id) {
                            $fk = $item->besichtigungstermin_id;
                            $link = \Yii::$app->urlManagerFrontend->baseUrl . '/termin_viewen';
                            $link .= '?id=' . $fk;
                            return Html::a('<span class="glyphicon glyphicon-flag"></span>', $link, ['title' => 'zum Termin im Frontend springen']);
                        }
                    }
                },
            ],
        ],
    ];
    ?>
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

</div>
