<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
?>
<div class="kunde-view">
    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->id) ?></h2>
        </div>
    </div>
    <div class="row">
        <?php
        $gridColumn = [
            'id',
            'telefon',
            'email',
            'angelegt_am',
            'aktualisiert_am',
            [
                'attribute' => 'angelegt_von',
                'label' => Yii::t('app', 'Angelegt von'),
                'value' => function($model, $id) {
                    return $model->vorname . ' ' . $model->nachname;
                },
            ],
            [
                'attribute' => 'aktualisiert_von',
                'label' => Yii::t('app', 'Aktualisiert von'),
                'value' => function($model, $id) {
                    if (!empty($model->aktualisiertVon))
                        return 'Makler ' . $model->aktualisiertVon->username;
                },
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
</div>