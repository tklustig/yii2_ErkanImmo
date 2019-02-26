<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Kunde */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kunde'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kunde-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Kunde').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        'l_plz_id',
        'geschlecht',
        'vorname',
        'nachname',
        'stadt',
        'strasse',
        'geburtsdatum',
        'solvenz',
        [
                'attribute' => 'bankverbindung.id',
                'label' => Yii::t('app', 'Bankverbindung')
            ],
        'angelegt_am',
        'aktualisiert_am',
        'angelegt_von',
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
if($providerAdminbesichtigungkunde->totalCount){
    $gridColumnAdminbesichtigungkunde = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
        [
                'attribute' => 'besichtigungstermin.id',
                'label' => Yii::t('app', 'Besichtigungstermin')
            ],
        [
                'attribute' => 'admin.id',
                'label' => Yii::t('app', 'Admin')
            ],
            ];
    echo Gridview::widget([
        'dataProvider' => $providerAdminbesichtigungkunde,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('app', 'Adminbesichtigungkunde')),
        ],
        'panelHeadingTemplate' => '<h4>{heading}</h4>{summary}',
        'toggleData' => false,
        'columns' => $gridColumnAdminbesichtigungkunde
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
                'attribute' => 'immobilien.id',
                'label' => Yii::t('app', 'Immobilien')
            ],
        [
                'attribute' => 'user.id',
                'label' => Yii::t('app', 'User')
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
                'attribute' => 'immobilien.id',
                'label' => Yii::t('app', 'Immobilien')
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
