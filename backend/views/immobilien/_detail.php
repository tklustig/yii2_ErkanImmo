<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
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
            'bezeichnung:ntext',
            'strasse',
            'wohnflaeche',
            'k_grundstuecksgroesse',
            'raeume',
            'geldbetrag',
            'v_nebenkosten',
            'k_provision',
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
            [
                'attribute' => 'lHeizungsart.id',
                'label' => Yii::t('app', 'L Heizungsart'),
            ],
            'balkon_vorhanden',
            'fahrstuhl_vorhanden',
            'sonstiges:ntext',
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