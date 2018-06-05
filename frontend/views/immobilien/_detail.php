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
            'bezeichnung:html',
            'strasse',
            'wohnflaeche',
            'raeume',
            'geldbetrag',
            'l_plz_id',
            [
                'attribute' => 'lStadt.stadt',
                'label' => Yii::t('app', 'Stadt'),
            ],
            [
                'attribute' => 'user.username',
                'label' => Yii::t('app', 'User'),
            ],
            [
                'attribute' => 'lArt.bezeichnung',
                'label' => Yii::t('app', 'Art'),
            ],
            'angelegt_am',
            'aktualisiert_am',
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