<?php
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ImmobilienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
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
    <p>
        <?= Html::a(Yii::t('app', 'Tiefergehende Suche'), '#', ['class' => 'btn btn-info search-button']) ?>
    </p>
    <div class="search-form" style="display:none">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php
    $dummy = 'id';
    /* $art=2 entpsricht einer Kaufimmobilie, $art=1 entspricht einer Mietimmobilie =>
      KAUF-Immobilie:
     */
    if ($art == 2) {
        $gridColumn = [
            [
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
            'k_grundstuecksgroesse',
            'k_provision',
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'balkon_vorhanden',
                'trueLabel' => 'nA',
                'falseLabel' => 'nA',
                'label' => '<span class="glyphicon glyphicon-piggy-bank"></span>' . '<br>Balkon vorhanden',
                'encodeLabel' => false,
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'fahrstuhl_vorhanden',
                'trueLabel' => 'nAc',
                'falseLabel' => 'nAc',
                'label' => '<span class="glyphicon glyphicon-circle-arrow-up"></span>' . '<br>Fahrstuhl vorhanden',
                'encodeLabel' => false,
            ],
            [
                'attribute' => 'l_heizungsart_id',
                'label' => Yii::t('app', 'Heizungsart'),
                'value' => function($model) {
                    if ($model->lHeizungsart) {
                        return $model->lHeizungsart->bezeichnung;
                    } else {
                        return NULL;
                    }
                },
            ],
        ];
        /* $art=1 entpsricht einer Kaufimmobilie, $art=2 entspricht einer Mietimmobilie =>
          MIET-Immobilie:
         */
    } else if ($art == 1) {
        $gridColumn = [
            [
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
            'v_nebenkosten',
            'balkon_vorhanden',
            'fahrstuhl_vorhanden',
            [
                'attribute' => 'l_heizungsart_id',
                'label' => Yii::t('app', 'Heizungsart'),
                'value' => function($model) {
                    if ($model->lHeizungsart) {
                        return $model->lHeizungsart->bezeichnung;
                    } else {
                        return NULL;
                    }
                },
            ],
        ];
    }
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
                'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> zur Übersicht', ['/immobilien/preview'], ['class' => 'btn btn-info']),
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
