<style>
    .upper{
        float:right;
    }
</style>
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bewerber'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bewerber-view">
    <div class="row">
        <div class="col-sm-8">
            <div class="box-body">
                <h2><?= Yii::t('app', 'Besichtigungstermin') . ' ' . Html::encode($this->title) ?></h2>
            </div>
            <div class="upper" style="margin-top: 15px">
                <?= Html::a(Yii::t('app', 'zur Immobilie'), ['immobilien/preview'], ['class' => 'btn btn-sucess']) ?>    
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
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box-body">
                <?php
                $gridColumn = [
                    'id',
                    'uhrzeit',
                    'Relevanz',
                    'angelegt_am',
                    'aktualisiert_am',
                    'angelegt_von',
                    'aktualisiert_von',
                    'Immobilien_id',
                ];
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => $gridColumn
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<div class="row"></div>