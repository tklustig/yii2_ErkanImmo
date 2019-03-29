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
            [
                'attribute' => 'telefon',
                'label' => Yii::t('app', 'Telefonnummer'),
                'value' => function($model, $id) {
                    return $model->telefon ? $model->telefon : 'wurde nicht hinterlegt';
                },
            ],
            [
                'attribute' => 'email',
                'label' => Yii::t('app', 'Mailadresse'),
                'value' => function($model, $id) {
                    return $model->email ? $model->email : 'wurde nicht hinterlegt';
                },
            ],
            [
                'attribute' => 'bankverbindung_id',
                'label' => Yii::t('app', 'Bankdaten hinterlegt'),
                'value' => function($model, $id) {
                    return $model->bankverbindung_id ? 'wurden hinterlegt' : 'wurden nicht hinterlegt';
                },
            ],
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