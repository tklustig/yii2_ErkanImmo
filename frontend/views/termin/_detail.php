<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
?>
<div class="mail-eingang-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->id) ?></h2>
        </div>
    </div>

    <div class="row">
        <?php
        $dummy = 'id';
        $gridColumn = [
            'id',
            'uhrzeit',
            'Relevanz',
            'angelegt_am',
            'aktualisiert_am',
            'angelegt_von',
            'aktualisiert_von',
            'Immobilien_id',
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
</div>

