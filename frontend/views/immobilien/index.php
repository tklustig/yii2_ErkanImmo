<?php
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ImmobilienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Immobilien');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="immobilien-index">

    <div class="container">

        <h2><?= Html::encode($this->title) ?></h2>
        <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

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
            'id_bild',
            'bezeichnung:ntext',
            'strasse',
            'l_plz_id',
            [
                'attribute' => 'l_stadt_id',
                'label' => Yii::t('app', 'L Stadt'),
                'value' => function($model) {
                    return $model->lStadt->id;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\frontend\models\LStadt::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'L stadt', 'id' => 'grid-immobilien-search-l_stadt_id']
            ],
            [
                'attribute' => 'user_id',
                'label' => Yii::t('app', 'User'),
                'value' => function($model) {
                    return $model->user->id;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-immobilien-search-user_id']
            ],
            [
                'attribute' => 'l_art_id',
                'label' => Yii::t('app', 'L Art'),
                'value' => function($model) {
                    return $model->lArt->id;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => \yii\helpers\ArrayHelper::map(\frontend\models\LArt::find()->asArray()->all(), 'id', 'id'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'L art', 'id' => 'grid-immobilien-search-l_art_id']
            ],
            /*   'angelegt_am',
              'aktualisiert_am',
              'angelegt_von',
              'aktualisiert_von',

             */
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
        Pjax::begin();
        ?>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumn,
            'hover' => true,
            'condensed' => true,
            'panel' => [
                "heading" => "<h3 class='panel-title'><i class='glyphicon glyphicon-globe'></i> " . $this->title . "</h3>",
                'type' => 'danger',
                'after' => Html::a('<strong><i class="glyphicon glyphicon-repeat"></i> Reset Grid </strong>', ['/immobilien/index'], ['class' => 'btn btn-success']),
                'footer' => false,
            ],
            'toggleDataOptions' => ['minCount' => 10],
            'options' => [
                'style' => 'overflow: auto; word-wrap: break-word;'
            ],
        ]);
        Pjax::end();
        ?>
    </div>
</div>
</div>

