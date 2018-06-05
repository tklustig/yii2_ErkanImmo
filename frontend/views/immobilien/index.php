<?php
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ImmobilienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Immobilien');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class='container-fluid'>

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a(Yii::t('app', 'Advance Search'), '#', ['class' => 'btn btn-warning search-button']) ?>
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
        [
            'attribute' => 'l_stadt_id',
            'label' => Yii::t('app', 'L Stadt'),
            'value' => function($model) {
                return $model->lStadt->stadt;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\frontend\models\LStadt::find()->asArray()->all(), 'id', 'stadt'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'L stadt', 'id' => 'grid-immobilien-search-l_stadt_id']
        ],
        'bezeichnung:html',
        'strasse',
        'wohnflaeche',
        'raeume',
        'geldbetrag',
        // 'l_plz_id',
        /*
          [
          'attribute' => 'user_id',
          'label' => Yii::t('app', 'User'),
          'value' => function($model) {
          return $model->user->id;
          },
          'filterType' => GridView::FILTER_SELECT2,
          'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->asArray()->all(), 'id', 'username'),
          'filterWidgetOptions' => [
          'pluginOptions' => ['allowClear' => true],
          ],
          'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-immobilien-search-user_id']
          ], */
        [
            'attribute' => 'l_art_id',
            'label' => Yii::t('app', 'L Art'),
            'value' => function($model) {
                return $model->lArt->bezeichnung;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\frontend\models\LArt::find()->asArray()->all(), 'id', 'bezeichnung'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'L art', 'id' => 'grid-immobilien-search-l_art_id']
        ],
        'angelegt_am',
        /*
          'aktualisiert_am',
          [
          'attribute' => 'angelegt_von',
          'label' => Yii::t('app', 'Angelegt Von'),
          'value' => function($model) {
          if ($model->angelegtVon) {
          return $model->angelegtVon->id;
          } else {
          return NULL;
          }
          },
          'filterType' => GridView::FILTER_SELECT2,
          'filter' => \yii\helpers\ArrayHelper::map(\frontend\models\User::find()->asArray()->all(), 'id', 'id'),
          'filterWidgetOptions' => [
          'pluginOptions' => ['allowClear' => true],
          ],
          'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-immobilien-search-angelegt_von']
          ],
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
          'filter' => \yii\helpers\ArrayHelper::map(\frontend\models\User::find()->asArray()->all(), 'id', 'id'),
          'filterWidgetOptions' => [
          'pluginOptions' => ['allowClear' => true],
          ],
          'filterInputOptions' => ['placeholder' => 'User', 'id' => 'grid-immobilien-search-aktualisiert_von']
          ],

         */
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
        ],
    ];
    Pjax::begin();
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
            "heading" => "<h3 class='panel-title'><i class='glyphicon glyphicon-globe'></i> " . $this->title . "</h3>",
            'type' => 'danger',
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> zur Hauptseite', ['/site/index'], ['class' => 'btn btn-info']),
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['/immobilien/index'], ['class' => 'btn btn-primary', 'title' => 'Setzt die GridView zurück']),
            'toggleDataOptions' => ['minCount' => 10],
        ],
        'toolbar' => [
            ['content' =>
                Html::a('<i class="fa fa-folder-open"></i>', ['/immobilien/index', 'bez' => 'umbenennen'], ['class' => 'btn btn-primary', 'title' => 'additional content'])
            ],
            '{export}',
            '{toggleData}'
        ],
        'toggleDataOptions' => ['minCount' => 10],
    ]);
    Pjax::end();
    ?>
</div>

