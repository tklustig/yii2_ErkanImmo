<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Rechnung */
?>
<div class="rechnung-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->id) ?></h2>
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn = [
            [
                'attribute' => 'angelegt_von',
                'label' => Yii::t('app', 'Angelegt von'),
                'value' => function($model, $id) {
                    return $model->angelegt_von ? 'Makler ' . $model->angelegtVon->username : 'nicht vorhanden';
                },
            ],
            [
                'attribute' => 'aktualisiert_von',
                'label' => Yii::t('app', 'Aktualisiert von'),
                'value' => function($model, $id) {
                    return $model->aktualisiert_von ? 'Makler ' . $model->aktualisiertVon->username : 'nicht vorhanden';
                },
            ],
            [
                'attribute' => 'angelegt_am',
                'label' => 'Angelegt am',
                'value' => function($model, $id) {
                    if (!empty($model->angelegt_am)) {
                        $datetime = $model->angelegt_am;
                        $giveBackValue = date('d-m-Y H:i:s', strtotime($datetime));
                    } else {
                        $giveBackValue = "nicht vorhanden";
                    }
                    return $giveBackValue;
                },
            ],
            [
                'attribute' => 'aktualisiert_am',
                'label' => 'Aktualisiert am',
                'value' => function($model, $id) {
                    if (!empty($model->aktualisiert_am)) {
                        $datetime = $model->aktualisiert_am;
                        $giveBackValue = date('d-m-Y H:i:s', strtotime($datetime));
                    } else {
                        $giveBackValue = "nicht vorhanden";
                    }
                    return $giveBackValue;
                },
            ],
            'rechnungPlain:html',
            'beschreibung:html'
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
</div>