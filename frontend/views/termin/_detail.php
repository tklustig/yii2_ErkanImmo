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
                'attribute' => 'aktualisiert_von',
                'label' => Yii::t('app', 'Aktualisiert von'),
                'value' => function($model, $id) {
                    $adminId = frontend\models\Adminbesichtigungkunde::findOne(['besichtigungstermin_id' => $model->id])->admin_id;
                    $makler = common\models\User::findOne(['id' => $adminId])->username;
                    return $model->aktualisiert_von ? $makler : 'wurde nicht hinterlegt';
                },
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
</div>

