<?php
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ImmobilienSearch */
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
<div class="container-fluid">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?= Html::a(Yii::t('app', 'Tiefergehende Suche'), '#', ['class' => 'btn btn-info search-button']) ?>
    </p>
    <div class="search-form" style="display:none">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php
    $dummy = 'id';
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
        [
            /*
              Hier wird das Bewerberbild in einer eigenen Spalte implementiert.Das jeweilige Bild liefert die Methode GetBewerberBild(model),welche
              drei JOINs und eine dynamische WHERE-Klausel enthält,die auf den FK id_person von bewerber prüft. Das Bild liegt physikalisch auf dem Webspace,
              dessen Zugriffspfade in der Datenbank in einer ganz bestimmten Reihenfolge hinterlegt sein müssen!
             */
            'attribute' => $dummy,
            'label' => Yii::t('app', ''),
            'format' => 'html', // sorgt dafür,dass das HTML im return gerendert wird
            'vAlign' => 'middle',
            'value' => function($model) {
                $bmp = '/bmp/';
                $tif = '/tif/';
                $png = '/png/';
                $psd = '/psd/';
                $pcx = '/pcx/';
                $gif = '/gif/';
                $jpeg = '/jpeg/';
                $jpg = '/jpg/';
                $ico = '/ico/';
                try {
                    $bilder = \frontend\models\Dateianhang::GetBild($model);
                    foreach ($bilder as $bild) {
                        if (preg_match($bmp, $bild->dateiname) || preg_match($tif, $bild->dateiname) || preg_match($png, $bild->dateiname) || preg_match($psd, $bild->dateiname) || preg_match($pcx, $bild->dateiname) || preg_match($gif, $bild->dateiname) || preg_match($jpeg, $bild->dateiname) || preg_match($jpg, $bild->dateiname) || preg_match($ico, $bild->dateiname)) {
                            $url = '@web/img/' . $bild->dateiname;
                        }
                    }
                    return Html::img($url, ['alt' => 'Bewerberbild nicht vorhanden', 'class' => 'img-circle', 'style' => 'width:225px;height:225px']);
                } catch (Exception $e) {
                    return;
                }
            }
        ],
        'bezeichnung:html',
        'sonstiges:html',
        'strasse',
        'wohnflaeche',
        'raeume',
        'geldbetrag',
        'k_grundstuecksgroesse',
        'k_provision',
        'v_nebenkosten',
        'balkon_vorhanden',
        'fahrstuhl_vorhanden',
        'l_plz_id',
        'stadt',
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
        [
            'attribute' => 'l_heizungsart_id',
            'label' => Yii::t('app', 'L Heizungsart'),
            'value' => function($model) {
                if ($model->lHeizungsart) {
                    return $model->lHeizungsart->id;
                } else {
                    return NULL;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\frontend\models\LHeizungsart::find()->asArray()->all(), 'id', 'id'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'L heizungsart', 'id' => 'grid-immobilien-search-l_heizungsart_id']
        ],
        'angelegt_am',
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
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->asArray()->all(), 'id', 'id'),
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
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->asArray()->all(), 'id', 'id'),
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
    <div class="container-fluid">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumn,
            'pjax' => true,
            'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-immobilien']],
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> zur Hauptseite', ['/site/index'], ['class' => 'btn btn-info']),
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
            ],
            // your toolbar can include the additional full export menu
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="fa fa-folder-open"></i>', ['/immobilien/index', 'bez' => 'umbenennen'], ['class' => 'btn btn-primary', 'title' => 'additional content'])
                ],
                '{export}',
                '{toggleData}'
            ],
            'toggleDataOptions' => ['minCount' => 10],
        ]);
        ?>
    </div>
</div>
