<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ImmobilienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Immobilien');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="immobilien-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Immobilien'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-info search-button']) ?>
    </p>
    <div class="search-form" style="display:none">
        <?=  $this->render('_search', ['model' => $searchModel]); ?>
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
        'bezeichnung:ntext',
        'strasse',
        'wohnflaeche',
        'raeume',
        'geldbetrag',
        'l_plz_id',
        [
                'attribute' => 'l_stadt_id',
                'label' => Yii::t('app', 'L Stadt'),
                'value' => function($model){                   
                    return $model->lStadt->id;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\backend\models\LStadt::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'L stadt', 'id' => 'grid-immobilien-search-l_stadt_id']
            ],
        [
                'attribute' => 'user_id',
                'label' => Yii::t('app', 'User'),
                'value' => function($model){                   
                    return $model->user->id;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\backend\models\User::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-immobilien-search-user_id']
            ],
        [
                'attribute' => 'l_art_id',
                'label' => Yii::t('app', 'L Art'),
                'value' => function($model){                   
                    return $model->lArt->id;                   
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\backend\models\LArt::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'L art', 'id' => 'grid-immobilien-search-l_art_id']
            ],
        'angelegt_am',
        'aktualisiert_am',
        [
                'attribute' => 'angelegt_von',
                'label' => Yii::t('app', 'Angelegt Von'),
                'value' => function($model){
                    if ($model->angelegtVon)
                    {return $model->angelegtVon->id;}
                    else
                    {return NULL;}
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\backend\models\User::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-immobilien-search-angelegt_von']
            ],
        [
                'attribute' => 'aktualisiert_von',
                'label' => Yii::t('app', 'Aktualisiert Von'),
                'value' => function($model){
                    if ($model->aktualisiertVon)
                    {return $model->aktualisiertVon->id;}
                    else
                    {return NULL;}
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\backend\models\User::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-immobilien-search-aktualisiert_von']
            ],
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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-immobilien']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
        ],
        // your toolbar can include the additional full export menu
        'toolbar' => [
            '{export}',
            ExportMenu::widget([
                'dataProvider' => $dataProvider,
                'columns' => $gridColumn,
                'target' => ExportMenu::TARGET_BLANK,
                'fontAwesome' => true,
                'dropdownOptions' => [
                    'label' => 'Full',
                    'class' => 'btn btn-default',
                    'itemsBefore' => [
                        '<li class="dropdown-header">Export All Data</li>',
                    ],
                ],
            ]) ,
        ],
    ]); ?>

</div>
