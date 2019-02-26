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
        'uhrzeit',
        'Relevanz',
        'angelegt_am',
        'aktualisiert_am',
        'angelegt_von',
        'aktualisiert_von',
        'Immobilien_id',
            [
                'attribute' => 'angelegtVon.id',
                'label' => Yii::t('app', 'Angelegt Von')
            ],
            'aktualisiert_am',
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
    </div>
</div>


