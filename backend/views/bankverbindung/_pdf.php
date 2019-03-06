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
            'angelegt_von',
            [
                'attribute' => 'aktualisiertVon.id',
                'label' => Yii::t('app', 'Aktualisiert Von')
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>

    <div class="row">
        <?php
        if ($providerKunde->totalCount) {
            $gridColumnKunde = [
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute' => 'id', 'visible' => false],
                'l_plz_id',
                'geschlecht',
                'vorname',
                'nachname',
                'stadt',
                'strasse',
                'geburtsdatum',
                'solvenz',
                'angelegt_am',
                'aktualisiert_am',
                'angelegt_von',
                [
                    'attribute' => 'aktualisiertVon.id',
                    'label' => Yii::t('app', 'Aktualisiert Von')
                ],
            ];
            echo Gridview::widget([
                'dataProvider' => $providerKunde,
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => Html::encode(Yii::t('app', 'Kunde')),
                ],
                'panelHeadingTemplate' => '<h4>{heading}</h4>{summary}',
                'toggleData' => false,
                'columns' => $gridColumnKunde
            ]);
        }
        ?>
    </div>
</div>
