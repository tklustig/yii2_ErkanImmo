<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Mail */
?>
<div class="mail-view">

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
                'attribute' => 'mailserver.serverHost',
                'label' => Yii::t('app', 'Mailserver'),
            ],
            [
                'attribute' => 'angelegt_am',
                'label' => Yii::t('app', 'Angelegt am'),
                'format' => ['datetime', 'php:d-M-Y H:i:s'],
                'hAlign' => 'center',
                'value' => function($model) {
                    $angelegt_am = new DateTime($model->angelegt_am);
                    if ($model->angelegt_am) {
                        return $angelegt_am;
                    } else {
                        return NULL;
                    }
                },
            ],
            [
                'attribute' => 'angelegtVon.username',
                'label' => Yii::t('app', 'Angelegt Von'),
            ],
            [
                'attribute' => 'aktualisiert_am',
                'label' => Yii::t('app', 'Aktualisiert am'),
                'format' => ['datetime', 'php:d-M-Y H:i:s'],
                'hAlign' => 'center',
                'value' => function($model) {
                    $aktualisiert_am = new DateTime($model->aktualisiert_am);
                    if ($model->aktualisiert_am) {
                        return $aktualisiert_am;
                    } else {
                        return NULL;
                    }
                },
            ],
            [
                'attribute' => 'aktualisiertVon.username',
                'label' => Yii::t('app', 'Aktualisiert Von'),
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
</div>