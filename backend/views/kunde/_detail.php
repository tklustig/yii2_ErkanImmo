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
                'label' => Yii::t('app', 'Bankdaten...'),
                'value' => function($model, $id) {
                    return $model->bankverbindung_id ? 'wurden hinterlegt' : 'wurden nicht hinterlegt';
                },
            ],
            /* [
              'attribute' => 'solvenz',
              'label' => 'ist Solvent',
              'format' => 'raw',
              'value' => $model->solvenz ? '<span class="label label-success">Ja</span>' : '<span class="label label-danger">Nein</span>',
              'widgetOptions' => [
              'pluginOptions' => [
              'onText' => 'Ja',
              'offText' => 'Nein',
              ]
              ],
              'valueColOptions' => ['style' => 'width:30%']
              ], */
            [
                'attribute' => 'geburtsdatum',
                'format' => 'html',
                'label' => Yii::t('app', 'Geburtsdatum'),
                'value' => function($model, $id) {
                    if ($model->geburtsdatum) {
                        $expression = new yii\db\Expression('NOW()');
                        $now = (new \yii\db\Query)->select($expression)->scalar();
                        $diff = strtotime($now) - strtotime($model->geburtsdatum);
                        $hours = floor($diff / (60 * 60));
                        $year = floor($hours / 24 / 365);
                        $output = date("d.m.Y", strtotime($model->geburtsdatum)) . '<br>' . $year . " Jahre alt";
                        return $output;
                    } else {
                        return NULL;
                    }
                },
            ],
            [
                'attribute' => 'angelegt_von',
                'label' => Yii::t('app', 'Angelegt von'),
                'format' => 'html',
                'value' => function($model, $id) {
                    return '<strong>Kunde</strong>' . ' ' . $model->geschlecht0->typus . ' ' . $model->vorname . ' ' . $model->nachname;
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