<?php
/* @var $this yii\web\View */
/* @var $searchModel backend\models\BankverbindungSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Bankverbindung');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="bankverbindung-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?php // echo Html::a(Yii::t('app', 'Create Bankverbindung'), ['create'], ['class' => 'btn btn-success'])  ?>
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
        'laenderkennung',
        'institut',
        'blz',
        'kontoNr',
        'iban',
        'bic',
        'angelegt_am',
        'aktualisiert_am',
        'angelegt_von',
        [
            'attribute' => 'aktualisiert_von',
            'label' => Yii::t('app', 'Aktualisiert Von'),
            'value' => function($model) {
                if ($model->aktualisiertVon) {
                    return $model->aktualisiertVon->id;
                } else {
                    return NULL;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->asArray()->all(), 'id', 'id'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-bankverbindung-search-aktualisiert_von']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
            'buttons' => [
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
