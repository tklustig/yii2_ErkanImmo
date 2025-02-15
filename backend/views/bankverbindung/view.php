<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bankverbindung'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bankverbindung-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Bankverbindung') . ' ' . Html::encode($this->title) ?></h2>
        </div>
        <div class="upper" style="margin-top: 15px">
            <?= Html::a(Yii::t('app', 'zur Bankverbindung'), ['/bankverbindung/index'], ['class' => 'btn btn-primary ']) ?>  
            <?= Html::a(Yii::t('app', 'Zu den Kunden'), ['/kunde/index'], ['class' => 'btn btn-success ']) ?>  
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
            'laenderkennung',
            'institut',
            'blz',
            'kontoNr',
            'iban',
            'bic',
            'angelegt_am',
            'aktualisiert_am',
            [
                'attribute' => 'aktualisiertVon.username',
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
    <div class="row">
        <h4>User<?= ' ' . Html::encode($this->title) ?></h4>
    </div>
    <div class="row">
        <?php
        if ($providerKunde->totalCount) {
            $gridColumnKunde = [
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute' => 'id', 'visible' => false],
                'l_plz_id',
                'geschlecht0.typus',
                'vorname',
                'nachname',
                'stadt',
                'strasse',
                [
                    'attribute' => 'geburtsdatum',
                    'label' => Yii::t('app', 'Geburtsdatum'),
                    'value' => function($model) {
                        $expression = new yii\db\Expression('NOW()');
                        $now = (new yii\db\Query)->select($expression)->scalar();
                        $diff = strtotime($now) - strtotime($model->geburtsdatum);
                        $hours = floor($diff / (60 * 60));
                        $year = floor($hours / 24 / 365);
                        $output = 'geboren am ' . date("d.m.Y", strtotime($model->geburtsdatum)) . ' => ' . $year . " Jahre alt";
                        return $output;
                    }
                ],
                [
                    'class' => 'kartik\grid\BooleanColumn',
                    'attribute' => 'solvenz',
                    'trueLabel' => 'Ja',
                    'falseLabel' => 'Nein',
                    'label' => 'ist Solvent',
                    'encodeLabel' => false,
                ],
                'angelegt_am',
                [
                    'attribute' => 'aktualisiertVon.username',
                    'label' => Yii::t('app', 'Aktualisiert Von')
                ],
            ];
            echo Gridview::widget([
                'dataProvider' => $providerKunde,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-kunde']],
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Kunde')),
                ],
                'columns' => $gridColumnKunde
            ]);
        }
        ?>

    </div>
</div>
