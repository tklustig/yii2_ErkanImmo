<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\bewerber\models\Bewerber */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bewerber'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bewerber-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Bewerber') . ' ' . Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn = [
            'angelegt_am',
            'id',
            'uhrzeit',
            'Relevanz',
            [
                'attribute' => 'angelegt_von',
                'label' => Yii::t('app', 'Makler'),
                'value' => function($model) {
                    ($model->angelegt_von) ? $value = $model->angelegtVon->nachname . ', ' . $model->angelegtVon->vorname : $value = 'kein Kunde vorhanden';
                    return $value;
                }
            ],
            'Immobilien_id',
            [
                'attribute' => 'Immobilien_id',
                'label' => Yii::t('app', 'Treffpunkt'),
                'value' => function($id, $model) {
                    $kundenId = frontend\models\Adminbesichtigungkunde::findOne(['besichtigungstermin_id' => $id])->kunde_id;
                    $wohnortKunde = \frontend\models\Kunde::findOne(['id' => $kundenId])->stadt;
                    return $wohnortKunde;
                }
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
    <div class="row">
    </div>
</div>


