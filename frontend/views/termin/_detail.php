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
            [
                'attribute' => 'Relevanz',
                'label' => 'Priorität hoch',
                'format' => 'raw',
                'value' => $model->Relevanz ? '<span class="label label-success">Ja</span>' : '<span class="label label-danger">Nein</span>',
                'widgetOptions' => [
                    'pluginOptions' => [
                        'onText' => 'Ja',
                        'offText' => 'Nein',
                    ]
                ],
                'valueColOptions' => ['style' => 'width:30%']
            ],
            'angelegt_am',
            'aktualisiert_am',
            [
                'attribute' => 'angelegt_von',
                'label' => Yii::t('app', 'wurde vereinbart mit'),
                'format' => 'html',
                'value' => function($model) {
                    $giveBack1 = $model->angelegtVon->geschlecht . ' ' . $model->angelegtVon->nachname . ', ' . $model->angelegtVon->vorname . '<br>';
                    $giveBack2 = 'wohnhaft in ' . $model->angelegtVon->stadt . '<br>' . $model->angelegtVon->strasse . '<br>' . 'Geburtsdatum:' . $model->angelegtVon->geburtsdatum;
                    ($model->angelegt_von) ? $bewerber = $giveBack1 . $giveBack2 : $bewerber = NULL;
                    return $bewerber;
                }
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
</div>

