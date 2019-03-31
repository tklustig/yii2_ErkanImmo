<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Rechnung */

?>
<div class="rechnung-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->id) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'id',
        'datumerstellung',
        'datumfaellig',
        'beschreibung:ntext',
        'geldbetrag',
        [
            'attribute' => 'mwst.id',
            'label' => Yii::t('app', 'Mwst'),
        ],
        [
            'attribute' => 'kunde.id',
            'label' => Yii::t('app', 'Kunde'),
        ],
        [
            'attribute' => 'makler.id',
            'label' => Yii::t('app', 'Makler'),
        ],
        [
            'attribute' => 'kopf.id',
            'label' => Yii::t('app', 'Kopf'),
        ],
        [
            'attribute' => 'angelegtVon.id',
            'label' => Yii::t('app', 'Angelegt Von'),
        ],
        [
            'attribute' => 'aktualisiertVon.id',
            'label' => Yii::t('app', 'Aktualisiert Von'),
        ],
        'angelegt_am',
        'aktualisiert_am',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>