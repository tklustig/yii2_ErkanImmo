<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
?>
<div class="bankverbindung-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->id) ?></h2>
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn = [
            'id',
            'angelegt_am',
            'aktualisiert_am',
            [
                'attribute' => 'angelegtVon.username',
                'label' => Yii::t('app', 'Angelegt von'),
            ],
            [
                'attribute' => 'aktualisiertVon.username',
                'label' => Yii::t('app', 'Aktualisiert von'),
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
</div>