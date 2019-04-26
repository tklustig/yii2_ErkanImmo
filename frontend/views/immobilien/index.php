<?php

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Immobilien');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <br><br>
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
                //'vAlign' => 'middle',
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
                        return Html::a(Html::img($url, ['alt' => 'picNotFound', 'class' => 'img-rounded', 'style' => 'width:225px;height:225px',]), ['/immobilien/show', 'filename' => $bild->dateiname], ['title' => 'Bild laden']);
                    } catch (Exception $e) {
                        return;
                    }
                }
            ],
            [
                'attribute' => 'bezeichnung',
                'format' => 'html',
                'label' => Yii::t('app', 'Bez'),
                'value' => function($model) {
                    $value = $model->bezeichnung;
                    $value = Cutten($value);
                    return $value;
                }
            ],
            [
                'attribute' => 'sonstiges',
                'format' => 'html',
                'label' => Yii::t('app', 'Sonstiges'),
                'value' => function($model) {
                    $value = $model->sonstiges;
                    $value = Cutten($value);
                    return $value;
                }
            ],
            'k_grundstuecksgroesse',
            [
                'attribute' => 'k_provision',
                'label' => Yii::t('app', 'Provision(%)'),
                'value' => function($model) {
                    $betrag = number_format(
                            $model->k_provision, // zu konvertierende zahl
                            2, // Anzahl an Nochkommastellen
                            ",", // Dezimaltrennzeichen
                            "."    // 1000er-Trennzeichen
                    );
                    ($model->k_provision) ? $value = $betrag : $value = NULL;
                    return $value;
                }
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'balkon_vorhanden',
                'trueLabel' => 'Ja',
                'falseLabel' => 'Nein',
                'label' => '<span class="fa fa-skyatlas"></span>' . '<br>Balkon',
                'encodeLabel' => false,
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'fahrstuhl_vorhanden',
                'trueLabel' => 'Ja',
                'falseLabel' => 'Nein',
                'label' => '<span class="fa fa-crop"></span>' . '<br>Fahrstuhl',
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
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{map}<br>{showDocument}',
                'buttons' => [
                    'map' => function ( $id, $model) {
                        return Html::a('<span class="glyphicon glyphicon-download"></span>Karte<span class="glyphicon glyphicon-upload">', ['/immobilien/map', 'id' => $model->id], ['style' => ['color' => 'red'], 'title' => 'Standort in Karte einblenden', 'data' => ['pjax' => '0']]);
                    },
                    'showDocument' => function ($id, $model) {
                        $doc = '/doc/';
                        $docx = '/docx/';
                        $txt = '/txt/';
                        $pdf = '/pdf/';
                        $odt = '/odt/';
                        $xls = '/xls/';
                        $xlsx = '/xlsx/';
                        $ppt = '/ppt/';
                        $arrayOfFK = array();
                        $filename = null;
                        if (!empty(\frontend\models\EDateianhang::findOne(['immobilien_id' => $model->id]))) {
                            $pk = \frontend\models\EDateianhang::findOne(['immobilien_id' => $model->id])->id;
                            $fileNames = frontend\models\Dateianhang::find()->where(['e_dateianhang_id' => $pk])->all();
                            foreach ($fileNames as $item) {
                                if (preg_match($doc, $item->dateiname) || preg_match($docx, $item->dateiname) || preg_match($txt, $item->dateiname) || preg_match($pdf, $item->dateiname) || preg_match($odt, $item->dateiname) || preg_match($xls, $item->dateiname) || preg_match($xlsx, $item->dateiname) || preg_match($ppt, $item->dateiname)) {
                                    $filename = $item->dateiname;
                                }
                            }
                            if ($filename != null)
                                return Html::a('<span class="glyphicon glyphicon-file"></span>', ['/immobilien/show', 'filename' => $filename], ['title' => 'Dokument anzeigen', 'data' => ['pjax' => '0']]);
                        }
                    },
                ],
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
                //'vAlign' => 'middle',
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
                        return Html::a(Html::img($url, ['alt' => 'picNotFound', 'class' => 'img-rounded', 'style' => 'width:225px;height:225px',]), ['/immobilien/show', 'filename' => $bild->dateiname], ['title' => 'Bild laden']);
                    } catch (Exception $e) {
                        return;
                    }
                }
            ],
            [
                'attribute' => 'bezeichnung',
                'format' => 'html',
                'label' => Yii::t('app', 'Bez'),
                'value' => function($model) {
                    $value = $model->bezeichnung;
                    $value = Cutten($value);
                    return $value;
                }
            ],
            [
                'attribute' => 'sonstiges',
                'format' => 'html',
                'label' => Yii::t('app', 'Sonstiges'),
                'value' => function($model) {
                    $value = $model->sonstiges;
                    $value = Cutten($value);
                    return $value;
                }
            ],
            [
                'attribute' => 'v_nebenkosten',
                'label' => Yii::t('app', 'Nebenkosten(€)'),
                'value' => function($model) {
                    $betrag = number_format(
                            $model->v_nebenkosten, // zu konvertierende zahl
                            2, // Anzahl an Nochkommastellen
                            ",", // Dezimaltrennzeichen
                            "."    // 1000er-Trennzeichen
                    );
                    return $betrag;
                },
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'balkon_vorhanden',
                'trueLabel' => 'Ja',
                'falseLabel' => 'Nein',
                'label' => '<span class="fa fa-skyatlas"></span>' . '<br>Balkon',
                'encodeLabel' => false,
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'fahrstuhl_vorhanden',
                'trueLabel' => 'Ja',
                'falseLabel' => 'Nein',
                'label' => '<span class="fa fa-crop"></span>' . '<br>Fahrstuhl',
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
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{map}<br>{showDocument}',
                'buttons' => [
                    'map' => function ( $id, $model) {
                        return Html::a('<span class="glyphicon glyphicon-download"></span>Karte<span class="glyphicon glyphicon-upload">', ['/immobilien/map', 'id' => $model->id], ['style' => ['color' => 'red'], 'title' => 'Standort in Karte einblenden', 'data' => ['pjax' => '0']]);
                    },
                    'showDocument' => function ($id, $model) {
                        $doc = '/doc/';
                        $docx = '/docx/';
                        $txt = '/txt/';
                        $pdf = '/pdf/';
                        $odt = '/odt/';
                        $xls = '/xls/';
                        $xlsx = '/xlsx/';
                        $ppt = '/ppt/';
                        $arrayOfFK = array();
                        $filename = null;
                        if (!empty(\frontend\models\EDateianhang::findOne(['immobilien_id' => $model->id]))) {
                            $pk = \frontend\models\EDateianhang::findOne(['immobilien_id' => $model->id])->id;
                            $fileNames = frontend\models\Dateianhang::find()->where(['e_dateianhang_id' => $pk])->all();
                            foreach ($fileNames as $item) {
                                if (preg_match($doc, $item->dateiname) || preg_match($docx, $item->dateiname) || preg_match($txt, $item->dateiname) || preg_match($pdf, $item->dateiname) || preg_match($odt, $item->dateiname) || preg_match($xls, $item->dateiname) || preg_match($xlsx, $item->dateiname) || preg_match($ppt, $item->dateiname)) {
                                    $filename = $item->dateiname;
                                }
                            }
                            if ($filename != null)
                                return Html::a('<span class="glyphicon glyphicon-file"></span>', ['/immobilien/show', 'filename' => $filename], ['title' => 'Dokument anzeigen', 'data' => ['pjax' => '0']]);
                        }
                    },
                ],
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
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> zur Übersicht', ['/immobilien/preview'], ['class' => 'btn btn-info']),
                'after' => Html::a('<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>Termin vereinbaren', ['/immobilien/termin', 'id' => $id], ['class' => 'btn btn-success']),
                'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
                'class' => 'danger'
            ],
            'toolbar' => [
                '{export}',
                '{toggleData}'
            ],
        ]);
        ?>
    </div>
</div>
<?php

function Cutten($string2BeChanged, $keyword = null, $replaceWith = null) {
    if ($keyword == null) {
        $keyword = array();
        $keyword[0] = "/\\\\/";
        $keyword[1] = "/rn/";
        //$keyword[2] = "/(?:\r?\n)/{2}";
        //$keyword[3] = "/(\r\n){2}/";
    }
    if ($replaceWith == null)
        $string = preg_replace($keyword, "", $string2BeChanged);
    else
        $string = preg_replace($keyword, $replaceWith, $string2BeChanged);
    return $string;
}
?>
