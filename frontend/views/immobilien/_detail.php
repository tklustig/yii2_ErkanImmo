<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Immobilien */

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
        'id_bild',
        'bezeichnung:ntext',
        'strasse',
        'l_plz_id',
        [
            'attribute' => 'lStadt.id',
            'label' => Yii::t('app', 'L Stadt'),
        ],
        [
            'attribute' => 'user.id',
            'label' => Yii::t('app', 'User'),
        ],
        [
            'attribute' => 'lArt.id',
            'label' => Yii::t('app', 'L Art'),
        ],
        'angelegt_am',
        'aktualisiert_am',
        'angelegt_von',
        'aktualisiert_von',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>