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
            'sonstiges:html',
            'strasse',
            'wohnflaeche',
            'raeume',
            'geldbetrag',
            'k_grundstuecksgroesse',
            'k_provision',
            'v_nebenkosten',
            'balkon_vorhanden',
            'fahrstuhl_vorhanden',
            'l_plz_id',
            'stadt',
            [
                'attribute' => 'user.id',
                'label' => Yii::t('app', 'User'),
            ],
            [
                'attribute' => 'lArt.id',
                'label' => Yii::t('app', 'L Art'),
            ],
            [
                'attribute' => 'lHeizungsart.id',
                'label' => Yii::t('app', 'L Heizungsart'),
            ],
            'angelegt_am',
            'aktualisiert_am',
            [
                'attribute' => 'angelegtVon.id',
                'label' => Yii::t('app', 'Angelegt Von'),
            ],
            [
                'attribute' => 'aktualisiertVon.id',
                'label' => Yii::t('app', 'Aktualisiert Von'),
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
</div>