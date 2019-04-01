<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\LRechnungsart */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'L Rechnungsart'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lrechnungsart-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'L Rechnungsart').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'id',
        'data:ntext',
        'art',
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
        'id',
        'datumerstellung',
        'datumfaellig',
        'beschreibung:ntext',
        'vorlage:ntext',
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
                'attribute' => 'kopf.id',
                'label' => Yii::t('app', 'Kopf')
            ],
                'rechnungPlain:ntext',
        [
                'attribute' => 'aktualisiertVon.id',
                'label' => Yii::t('app', 'Aktualisiert Von')
            ],
        [
                'attribute' => 'angelegtVon.id',
                'label' => Yii::t('app', 'Angelegt Von')
            ],
        'aktualisiert_am',
        'angelegt_am',
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
