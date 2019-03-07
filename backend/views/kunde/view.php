<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

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
            'lPlz.ort',
            'geschlecht',
            'vorname',
            'nachname',
            'stadt',
            'strasse',
            'geburtsdatum',
            'solvenz',
            [
                'attribute' => 'bankverbindung.institut',
                'label' => Yii::t('app', 'Bankverbindung'),
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
