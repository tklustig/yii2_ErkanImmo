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
        <div class="col-sm-8">
            <h2><?= Yii::t('app', 'L Rechnungsart').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-4" style="margin-top: 15px">
<?=             
             Html::a('<i class="fa glyphicon glyphicon-hand-up"></i> ' . Yii::t('app', 'PDF'), 
                ['pdf', 'id' => $model->id],
                [
                    'class' => 'btn btn-danger',
                    'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => Yii::t('app', 'Will open the generated PDF file in a new window')
                ]
            )?>
            <?= Html::a(Yii::t('app', 'Save As New'), ['save-as-new', 'id' => $model->id], ['class' => 'btn btn-info']) ?>            
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>
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
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-rechnung']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Rechnung')),
        ],
        'columns' => $gridColumnRechnung
    ]);
}
?>

    </div>
</div>
