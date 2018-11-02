<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
?>
<div class="immobilien-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->id) ?></h2>
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn = [
            ['attribute' => 'id', 'visible' => false],
            'bezeichnung:html',
            'strasse',
            'wohnflaeche',
            [
                'attribute' => 'geldbetrag',
                'label' => Yii::t('app', 'Mieteinahmen(€)'),
                'value' => function($model) {
                    $betrag = number_format(
                            $model->geldbetrag, // zu konvertierende zahl
                            2, // Anzahl an Nochkommastellen
                            ",", // Dezimaltrennzeichen
                            "."    // 1000er-Trennzeichen
                    );
                    ($model->geldbetrag) ? $value = $betrag : $value = NULL;
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
                    ($model->v_nebenkosten) ? $value = $betrag : $value = NULL;
                    return $value;
                }
            ],
            'raeume',
            [
                'attribute' => 'l_plz_id',
                'label' => Yii::t('app', 'Plz'),
                'value' => function($model) {
                    return $model->lPlz->plz;
                },
            ],
            [
                'attribute' => 'stadt',
                'label' => Yii::t('app', 'Stadt'),
            ],
            [
                'attribute' => 'user.username',
                'label' => Yii::t('app', 'User'),
            ],
            [
                'attribute' => 'lHeizungsart.bezeichnung',
                'label' => Yii::t('app', 'Heizungsart'),
            ],
            [
                'attribute' => 'balkon_vorhanden',
                'format' => 'raw',
                'value' => $model->balkon_vorhanden ? '<span class="label label-success">Ja</span>' : '<span class="label label-danger">Nein</span>',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'onText' => 'Ja',
                        'offText' => 'Nein',
                    ]
                ],
                'valueColOptions' => ['style' => 'width:30%']
            ],
            [
                'attribute' => 'fahrstuhl_vorhanden',
                'format' => 'raw',
                'value' => $model->fahrstuhl_vorhanden ? '<span class="label label-success">Ja</span>' : '<span class="label label-danger">Nein</span>',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'onText' => 'Ja',
                        'offText' => 'Nein',
                    ]
                ],
                'valueColOptions' => ['style' => 'width:30%']
            ],
            'sonstiges:html',
            [
                'attribute' => 'angelegt_am',
                'label' => Yii::t('app', 'Angelegt am'),
                'format' => ['datetime', 'php:d-M-Y H:i:s'],
                'hAlign' => 'center',
                'value' => function($model) {
                    $angelegt_am = new DateTime($model->angelegt_am);
                    if ($model->angelegt_am) {
                        return $angelegt_am;
                    } else {
                        return NULL;
                    }
                },
            ],
            [
                'attribute' => 'aktualisiert_am',
                'label' => Yii::t('app', 'Aktualisiert am'),
                'format' => ['datetime', 'php:d-M-Y H:i:s'],
                'hAlign' => 'center',
                'value' => function($model) {
                    $aktualisiert_am = new DateTime($model->aktualisiert_am);
                    if ($model->aktualisiert_am) {
                        return $aktualisiert_am;
                    } else {
                        return NULL;
                    }
                },
            ],
            [
                'attribute' => 'angelegtVon.username',
                'label' => Yii::t('app', 'angelegt von'),
            ],
            [
                'attribute' => 'aktualisiertVon.username',
                'label' => Yii::t('app', 'aktualisiert von'),
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
</div>

