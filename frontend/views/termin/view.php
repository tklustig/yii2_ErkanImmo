<style>
    .upper{
        float:right;
    }
</style>
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bewerber'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bewerber-view">
    <div class="row">
        <div class="col-sm-12">
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
        <div class="col-md-12">
            <center><h3>Unser Makler Herr/Frau <?= $model->angelegtVon->username ?> wird sich am <?= $model->uhrzeit ?> Uhr bei Ihnen vor Ort in  <?= $wohnortKunde ?> treffen, um die Immobilie in <?= $immoPlace ?> zu begutachten.</h3>
                <p>Pushen sie auf den weiß-grauen PDF Button, um ein Dokument für Ihre Unterlagen zu erstellen.</p></center>
        </div>
        <div class="col-md-12">
            <div class="box-body">
                <?php
                $gridColumn = [
                    'id',
                    'uhrzeit',
                    'Relevanz',
                    'angelegt_am',
                    [
                        'attribute' => 'angelegt_von',
                        'label' => Yii::t('app', 'Makler'),
                        'value' => function($model) {
                            ($model->angelegt_von) ? $value = $model->angelegtVon->username : $value = 'kein Makler gewählt';
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
        </div>
    </div>
    <div class="row"></div>