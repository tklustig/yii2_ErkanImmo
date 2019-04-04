<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Firma */
?>
<div class="firma-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->id) ?></h2>
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn = [
            'id',
            [
                'attribute' => 'lPlz.plz',
                'label' => Yii::t('app', 'Plz'),
            ],
            'geschaeftsfuehrer',
            'prokurist',
            'umsatzsteuerID',
            'anzahlMitarbeiter',
            [
                'attribute' => 'angelegtVon.username',
                'label' => Yii::t('app', 'Angelegt Von'),
            ],
            [
                'attribute' => 'aktualisiertVon.username',
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