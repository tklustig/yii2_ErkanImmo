<?php

use kartik\grid\GridView;
use yii\data\ArrayDataProvider;

$dataProvider = new ArrayDataProvider([
    'allModels' => $model->besichtigungstermins,
    'key' => function($model) {
        return ['id' => $model->id, 'Immobilien_id' => $model->Immobilien_id];
    }
        ]);
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    ['attribute' => 'id', 'visible' => false],
    [
        'attribute' => 'uhrzeit',
        'label' => Yii::t('app', 'Termin am'),
        'format' => ['datetime', 'php:d-M-Y H:i:s'],
        'hAlign' => 'center',
        'value' => function($model) {
            $uhrzeit = new DateTime($model->uhrzeit);
            if ($model->uhrzeit) {
                return $uhrzeit;
            } else {
                return NULL;
            }
        },
    ],
    [
        'attribute' => 'Relevanz',
        'label' => Yii::t('app', 'Dringlich'),
        'hAlign' => 'center',
        'value' => function($model) {
            if ($model->Relevanz == 1) {
                $ausgabe = "Ja";
            } else {
                $ausgabe = "Nein";
            }
            return $ausgabe;
        },
    ],
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
        'label' => Yii::t('app', 'Makler')
    ],
    [
        'attribute' => '',
        'label' => Yii::t('app', 'wurde vereinbart mit'),
        'format' => 'html',
        'value' => function($model, $id) {
            $bewerber='';
            if (!empty(\frontend\models\Kundeimmobillie::findOne(['immobilien_id' => $model->id]))) {
                $idKuImmo = \frontend\models\Kundeimmobillie::findOne(['immobilien_id' => $model->id])->kunde_id;
                $geschlecht = \frontend\models\Kunde::findOne(['id' => $idKuImmo])->geschlecht;
                $nachname = \frontend\models\Kunde::findOne(['id' => $idKuImmo])->nachname;
                $vorname = \frontend\models\Kunde::findOne(['id' => $idKuImmo])->vorname;
                $stadt = \frontend\models\Kunde::findOne(['id' => $idKuImmo])->stadt;
                $strasse = \frontend\models\Kunde::findOne(['id' => $idKuImmo])->strasse;
                $gebDat = \frontend\models\Kunde::findOne(['id' => $idKuImmo])->geburtsdatum;
                $giveBack1 = $geschlecht . ' ' . $nachname . ', ' . $vorname . '<br>';
                $giveBack2 = 'wohnhaft in ' . $stadt . '<br>' . $strasse . '<br>' . 'Geburtsdatum:' . $gebDat;
                ($model->angelegt_von) ? $bewerber = $giveBack1 . $giveBack2 : $bewerber = NULL;
            }

            return $bewerber;
        }
    ],
];

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
    'containerOptions' => ['style' => 'overflow: auto'],
    'pjax' => true,
    'beforeHeader' => [
        [
            'options' => ['class' => 'skip-export']
        ]
    ],
    'export' => [
        'fontAwesome' => true
    ],
    'bordered' => true,
    'striped' => true,
    'condensed' => true,
    'responsive' => true,
    'hover' => true,
    'showPageSummary' => false,
    'persistResize' => false,
]);
