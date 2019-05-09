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
                <?= Html::a(Yii::t('app', 'zur Übersicht'), ['site/index'], ['class' => 'btn btn-sucess']) ?>    
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
            <?php
            if (!empty(\frontend\models\Adminbesichtigungkunde::findOne(['kunde_id' => $model->angelegt_von]))) {
                $maklerId = \frontend\models\Adminbesichtigungkunde::findOne(['kunde_id' => $model->angelegt_von])->admin_id;
                $maklerName = \common\models\User::findOne(['id' => $maklerId])->username;
            } else
                $maklerName = "Unknown";
            ?>
            <center><h3>Unser Makler Herr/Frau <?= $maklerName ?> wird sich am <?= $model->uhrzeit ?> Uhr bei Ihnen vor Ort in  <?= $wohnortKunde ?> treffen, um die Immobilie in <?= $immoPlace ?> zu begutachten.</h3>
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
                        'label' => Yii::t('app', 'Kunde'),
                        'value' => function($model, $id) {
                            $fk = \frontend\models\Adminbesichtigungkunde::findOne(['besichtigungstermin_id' => $model->id])->kunde_id;
                            $name = \frontend\models\Kunde::findOne(['id' => $fk])->vorname . ',' . \frontend\models\Kunde::findOne(['id' => $fk])->nachname;
                            ($model->angelegt_von) ? $value = $name : $value = 'kein Kunde vorhanden';
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