<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
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
                'attribute' => 'bodytext',
                'label' => Yii::t('app', 'Mailinhalt'),
                'format' => 'html',
                'hAlign' => 'center',
                'value' => function($model) {
                    return $model->angelegt_am ? $model->bodytext : NULL;
                }
            ],
            [
                'attribute' => 'mailserver.serverHost',
                'label' => Yii::t('app', 'Mailserver'),
            ],
            [
                'attribute' => 'dummy',
                'label' => Yii::t('app', 'hat Anhänge'),
                'value' => function($model, $id) {
                    if (!empty(frontend\models\EDateianhang::findOne(['mail_id' => $model->id])))
                        $giveBackValue = 'JA - sie können über die Büroklammer angezeigt werden.';
                    else
                        $giveBackValue = 'NEIN';
                    return $giveBackValue;
                }
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
                }
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
                }
            ],
            [
                'attribute' => 'aktualisiertVon.username',
                'label' => Yii::t('app', 'Aktualisiert Von'),
            ]
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
</div>