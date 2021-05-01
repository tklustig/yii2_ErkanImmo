<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\Session;
use kartik\widgets\Alert;

$this->title = Yii::t('app', 'Immobilien');
$this->params['breadcrumbs'][] = $this->title;
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class='container-fluid'>
    <br><br><br>
    <?php
//Hier werden alle Flashnachrichten ausgegeben
    $session = Yii::$app->session;
    $MessageArt = Alert::TYPE_WARNING;
    foreach ($session->getAllFlashes() as $flash) {
        if (count($flash) > 2) {
            ?><?=
            generateOutput($MessageArt, implode("<br/><hr/><br/>", $flash));
        } else {
            foreach ($flash as $ausgabe) {
                ?><?=
                generateOutput($MessageArt, $ausgabe);
            }
        }
    }
    ?>
    <h1><?= Html::encode($this->title) ?></h1>
    <center><h2>Verkaufsobjekte</h2></center>
    <p>
        <?= Html::a(Yii::t('app', 'Tiefergehende Suche'), '#', ['class' => 'btn btn-warning search-button']) ?>
    </p>
    <div class="search-form" style="display:none">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php
    $dummy = 'id';
    $gridColumn_verkauf = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'width' => '50px',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('_expand_verkauf', ['model' => $model]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true
        ],
        'id',
        [
            /*
              Hier wird das Immobild in einer eigenen Spalte implementiert.Das jeweilige Bild liefert die Methode GetBild(model),welche
              drei JOINs und eine dynamische WHERE-Klausel enthält.
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
                    return Html::img($url, ['alt' => 'Immobild nicht vorhanden', 'class' => 'img-circle', 'style' => 'width:225px;height:225px']);
                } catch (Exception $e) {
                    return;
                }
            }
        ],
        [
            'attribute' => 'stadt',
            'label' => Yii::t('app', 'Stadt'),
            'value' => function($model) {
                return $model->stadt;
            },
        ],
        //'bezeichnung:html',
        // 'strasse',
        'wohnflaeche',
        'raeume',
        [
            'attribute' => 'geldbetrag',
            'label' => Yii::t('app', 'Kaufpreis(€)'),
            'value' => function($model) {
                $betrag = number_format(
                        $model->geldbetrag, // zu konvertierende zahl
                        2, // Anzahl an Nochkommastellen
                        ",", // Dezimaltrennzeichen
                        "."    // 1000er-Trennzeichen
                );
                return $betrag;
            },
        ],
        'k_grundstuecksgroesse',
        /*
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
          return $betrag;
          },
          ],
         */
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
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\frontend\models\LHeizungsart::find()->asArray()->all(), 'id', 'bezeichnung'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Heizungsart', 'id' => 'grid-immobilien-search']
        ],
        [
            'class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'balkon_vorhanden',
            'trueLabel' => 'Ja',
            'falseLabel' => 'Nein',
            'label' => '<span class="glyphicon glyphicon-piggy-bank"></span>' . '<br>Balkon vorhanden',
            'encodeLabel' => false,
        ],
        [
            'class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'fahrstuhl_vorhanden',
            'trueLabel' => 'Ja',
            'falseLabel' => 'Nein',
            'label' => '<span class="glyphicon glyphicon-circle-arrow-up"></span>' . '<br>Fahrstuhl vorhanden',
            'encodeLabel' => false,
        ],
        [
            'attribute' => 'id',
            'contentOptions' => ['class' => 'id_breite_css', 'style' => 'width:50px;'],
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => true,
            'headerOptions' => ['width' => '80'],
            'vAlign' => 'top',
            'header' => '',
            'template' => '{termin}',
            'buttons' => [
                'termin' => function ($model, $id) {
                    return Html::a('<span class="fa fa-spinner fa-pulse fa-3x fa-fw"></span>', ['immobilien/termin', 'id' => $id->id], ['title' => 'nicht im Preis inbegriffen', 'data' => ['pjax' => '0']]);
                },
            ],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}<br>{update}<br>{deleted}<br>{showDocument}<br>{deletion}',
            'buttons' => [
                'save-as-new' => function ($url) {
                    return Html::a('<span class="glyphicon glyphicon-copy"></span>', $url, ['title' => 'Duplizieren']);
                },
                'deleted' => function ($model, $id) {
                    return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['/immobilien/deleted', 'id' => $id->id], ['title' => 'Ohne Rückfrage löschen']);
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
                'deletion' => function ($id, $model) {
                    $arrayOfFilenames = array();
                    $filename = null;
                    if (!empty(\frontend\models\EDateianhang::findOne(['immobilien_id' => $model->id]))) {
                        $pk = \frontend\models\EDateianhang::findOne(['immobilien_id' => $model->id])->id;
                        $fileNames = frontend\models\Dateianhang::find()->where(['e_dateianhang_id' => $pk])->all();
                        foreach ($fileNames as $item) {
                            array_push($arrayOfFilenames, $item->dateiname);
                        }
                        if (count($arrayOfFilenames) > 0)
                            return Html::a('<span class="glyphicon glyphicon-remove-sign"></span>', ['/immobilien/deletion', 'id' => $model->id], ['title' => 'Uploads löschen', 'data' => ['pjax' => '0']]);
                    }
                },
            ],
        ],
    ];
    $gridColumn_vermieten = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'width' => '50px',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('_expand_vermieten', ['model' => $model]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true
        ],
        'id',
        [
            /*
              Hier wird das Immobild in einer eigenen Spalte implementiert.Das jeweilige Bild liefert die Methode GetBild(model),welche
              zwei JOINs und eine dynamische WHERE-Klausel enthält,die auf den FK immobilien_id von e_dateianhang prüft. */
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
                    return Html::img($url, ['alt' => 'Immobild nicht vorhanden', 'class' => 'img-circle', 'style' => 'width:225px;height:225px']);
                } catch (Exception $e) {
                    return;
                }
            }
        ],
        [
            'attribute' => 'stadt',
            'label' => Yii::t('app', 'Stadt'),
            'value' => function($model) {
                return $model->stadt;
            },
        ],
        //'bezeichnung:html',
        'strasse',
        'wohnflaeche',
        'raeume',
        [
            'attribute' => 'geldbetrag',
            'label' => Yii::t('app', 'Mietpreis(Netto)'),
            'value' => function($model) {
                $betrag = number_format(
                        $model->geldbetrag, // zu konvertierende zahl
                        2, // Anzahl an Nochkommastellen
                        ",", // Dezimaltrennzeichen
                        "."    // 1000er-Trennzeichen
                );
                return $betrag;
            },
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
            'attribute' => 'l_heizungsart_id',
            'label' => Yii::t('app', 'Heizungsart'),
            'value' => function($model) {
                if ($model->lHeizungsart) {
                    return $model->lHeizungsart->bezeichnung;
                } else {
                    return NULL;
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => \yii\helpers\ArrayHelper::map(\frontend\models\LHeizungsart::find()->asArray()->all(), 'id', 'bezeichnung'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Heizungsart', 'id' => 'grid-immobilien-search-l_2']
        ],
        [
            'class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'balkon_vorhanden',
            'trueLabel' => 'Ja',
            'falseLabel' => 'Nein',
            'label' => '<span class="glyphicon glyphicon-piggy-bank"></span>' . '<br>Balkon vorhanden',
            'encodeLabel' => false,
        ],
        [
            'class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'fahrstuhl_vorhanden',
            'trueLabel' => 'Ja',
            'falseLabel' => 'Nein',
            'label' => '<span class="glyphicon glyphicon-circle-arrow-up"></span>' . '<br>Fahrstuhl vorhanden',
            'encodeLabel' => false,
        ],
        [
            'attribute' => 'id',
            'contentOptions' => ['class' => 'id_breite_css', 'style' => 'width:50px;'],
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => true,
            'headerOptions' => ['width' => '80'],
            'vAlign' => 'top',
            'header' => '',
            'template' => '{termin}',
            'buttons' => [
                'termin' => function ($model, $id) {
                    return Html::a('<span class="fa fa-spinner fa-pulse fa-3x fa-fw"></span>', ['immobilien/termin', 'id' => $id->id], ['title' => 'nicht im Preis inbegriffen', 'data' => ['pjax' => '0']]);
                },
            ],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}<br>{update}<br>{deleted}<br>{showDocument}<br>{deletion}',
            'buttons' => [
                'save-as-new' => function ($url) {
                    return Html::a('<span class="glyphicon glyphicon-copy"></span>', $url, ['title' => 'Duplizieren']);
                },
                'deleted' => function ($id, $model) {
                    return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['/immobilien/deleted', 'id' => $model->id], ['title' => 'Ohne Rückfrage löschen']);
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
                'deletion' => function ($id, $model) {
                    $arrayOfFilenames = array();
                    $filename = null;
                    if (!empty(\frontend\models\EDateianhang::findOne(['immobilien_id' => $model->id]))) {
                        $pk = \frontend\models\EDateianhang::findOne(['immobilien_id' => $model->id])->id;
                        $fileNames = frontend\models\Dateianhang::find()->where(['e_dateianhang_id' => $pk])->all();
                        foreach ($fileNames as $item) {
                            array_push($arrayOfFilenames, $item->dateiname);
                        }
                        if (count($arrayOfFilenames) > 0)
                            return Html::a('<span class="glyphicon glyphicon-remove-sign"></span>', ['/immobilien/deletion', 'id' => $model->id], ['title' => 'Uploads löschen', 'data' => ['pjax' => '0']]);
                    }
                },
            ],
        ],
    ];
    ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider_verkauf,
        'filterModel' => $searchModel,
        'columns' => $gridColumn_verkauf,
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
    ?>
    <center><h2>Mietobjekte</h2></center>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider_vermieten,
        'filterModel' => $searchModel,
        'columns' => $gridColumn_vermieten,
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
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> zur Hauptseite', ['/site/index'], ['class' => 'btn btn-info']),
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['/immobilien/index'], ['class' => 'btn btn-primary', 'title' => 'Setzt die GridView zurück']),
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
<?php

function generateOutput($type, $content) {
    return Alert::widget(['type' => $type,
                'title' => 'Information',
                'icon' => 'glyphicon glyphicon-exclamation-sign',
                'body' => $content,
                'showSeparator' => true,
    ]);
}
?>

