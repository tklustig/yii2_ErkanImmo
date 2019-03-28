<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Kopf */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kopf'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kopf-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Kopf').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        'data:ntext',
        [
                'attribute' => 'user.id',
                'label' => Yii::t('app', 'User')
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
if($providerRechnung->totalCount){
    $gridColumnRechnung = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
        'datumerstellung',
        'datumfaellig',
        'beschreibung:ntext',
        'geldbetrag',
        [
                'attribute' => 'mwst.id',
                'label' => Yii::t('app', 'Mwst')
            ],
        [
                'attribute' => 'kunde.id',
                'label' => Yii::t('app', 'Kunde')
            ],
        [
                'attribute' => 'makler.id',
                'label' => Yii::t('app', 'Makler')
            ],
                [
                'attribute' => 'angelegtVon.id',
                'label' => Yii::t('app', 'Angelegt Von')
            ],
        [
                'attribute' => 'aktualisiertVon.id',
                'label' => Yii::t('app', 'Aktualisiert Von')
            ],
        'angelegt_am',
        'aktualisiert_am',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerRechnung,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('app', 'Rechnung')),
        ],
        'panelHeadingTemplate' => '<h4>{heading}</h4>{summary}',
        'toggleData' => false,
        'columns' => $gridColumnRechnung
    ]);
}
?>
    </div>
</div>
