<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\db\Query;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kunde'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kunde-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Kunde') . ' ' . Html::encode($this->title) ?></h2>
        </div>
        <div class="upper" style="margin-top: 15px">
            <?= Html::a(Yii::t('app', 'zur Übersicht'), ['index', 'id' => $model->id], ['class' => 'btn btn-success ']) ?>      
            <?=
            Html::a('<i class="fa glyphicon glyphicon-hand-up"></i> ' . Yii::t('app', 'PDF'), ['pdf', 'id' => $model->id], [
                'class' => 'btn btn-default',
                'target' => '_blank',
                'data-toggle' => 'tooltip',
                'title' => Yii::t('app', 'Will open the generated PDF file in a new window')
                    ]
            )
            ?>        
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn = [
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'l_plz_id',
                'label' => 'Wohnhaft in',
                'format' => 'raw',
                'value' => $model->lPlz->plz . ' ' . $model->stadt
            ],
            'geschlecht0.typus',
            'vorname',
            'nachname',
            'strasse',
            [
                'attribute' => 'geburtsdatum',
                'label' => Yii::t('app', 'Geburtsdatum'),
                'value' => function($model) {
                    $expression = new yii\db\Expression('NOW()');
                    $now = (new Query)->select($expression)->scalar();
                    $diff = strtotime($now) - strtotime($model->geburtsdatum);
                    $hours = floor($diff / (60 * 60));
                    $year = floor($hours / 24 / 365);
                    $output = 'geboren am ' . date("d.m.Y", strtotime($model->geburtsdatum)) . ' => ' . $year . " Jahre alt";
                    return $output;
                }
            ],
            [
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
            ],
            [
                'attribute' => 'bankverbindung.institut',
                'label' => Yii::t('app', 'Bankverbindung'),
            ],
            [
                'attribute' => 'aktualisiert_von',
                'label' => 'Aktualisiert von',
                'format' => 'raw',
                'value' => $model->aktualisiert_von ? $model->aktualisiertVon->username : null,
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
