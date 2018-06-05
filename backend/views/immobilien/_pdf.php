<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Immobilien */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Immobilien'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="immobilien-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Immobilien').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        'id_bild',
        'bezeichnung:ntext',
        'strasse',
        'wohnflaeche',
        'raeume',
        'geldbetrag',
        'l_plz_id',
        [
                'attribute' => 'lStadt.id',
                'label' => Yii::t('app', 'L Stadt')
            ],
        [
                'attribute' => 'user.id',
                'label' => Yii::t('app', 'User')
            ],
        [
                'attribute' => 'lArt.id',
                'label' => Yii::t('app', 'L Art')
            ],
        'angelegt_am',
        'aktualisiert_am',
        [
                'attribute' => 'angelegtVon.id',
                'label' => Yii::t('app', 'Angelegt Von')
            ],
        [
                'attribute' => 'aktualisiertVon.id',
                'label' => Yii::t('app', 'Aktualisiert Von')
            ],
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
    
    <div class="row">
<?php
if($providerBesichtigungstermin->totalCount){
    $gridColumnBesichtigungstermin = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
        'l_plz_id',
        'strasse',
        'uhrzeit',
        'Relevanz',
        [
                'attribute' => 'lStadt.id',
                'label' => Yii::t('app', 'L Stadt')
            ],
        'angelegt_am',
        'aktualisiert_am',
        [
                'attribute' => 'angelegtVon.id',
                'label' => Yii::t('app', 'Angelegt Von')
            ],
        [
                'attribute' => 'aktualisiertVon.id',
                'label' => Yii::t('app', 'Aktualisiert Von')
            ],
            ];
    echo Gridview::widget([
        'dataProvider' => $providerBesichtigungstermin,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('app', 'Besichtigungstermin')),
        ],
        'panelHeadingTemplate' => '<h4>{heading}</h4>{summary}',
        'toggleData' => false,
        'columns' => $gridColumnBesichtigungstermin
    ]);
}
?>
    </div>
    
    <div class="row">
<?php
if($providerEDateianhang->totalCount){
    $gridColumnEDateianhang = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
                [
                'attribute' => 'user.id',
                'label' => Yii::t('app', 'User')
            ],
        [
                'attribute' => 'kunde.id',
                'label' => Yii::t('app', 'Kunde')
            ],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerEDateianhang,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('app', 'E Dateianhang')),
        ],
        'panelHeadingTemplate' => '<h4>{heading}</h4>{summary}',
        'toggleData' => false,
        'columns' => $gridColumnEDateianhang
    ]);
}
?>
    </div>
    
    <div class="row">
<?php
if($providerKundeimmobillie->totalCount){
    $gridColumnKundeimmobillie = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
        [
                'attribute' => 'kunde.id',
                'label' => Yii::t('app', 'Kunde')
            ],
            ];
    echo Gridview::widget([
        'dataProvider' => $providerKundeimmobillie,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('app', 'Kundeimmobillie')),
        ],
        'panelHeadingTemplate' => '<h4>{heading}</h4>{summary}',
        'toggleData' => false,
        'columns' => $gridColumnKundeimmobillie
    ]);
}
?>
    </div>
</div>
