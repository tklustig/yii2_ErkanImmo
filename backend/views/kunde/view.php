<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Kunde */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kunde'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kunde-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Kunde').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
<?=             
             Html::a('<i class="fa glyphicon glyphicon-hand-up"></i> ' . Yii::t('app', 'PDF'), 
                ['pdf', 'id' => $model->id],
                [
                    'class' => 'btn btn-danger',
                    'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => Yii::t('app', 'Will open the generated PDF file in a new window')
                ]
            )?>
            
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'visible' => false],
        'l_plz_id',
        'geschlecht',
        'vorname',
        'nachname',
        'stadt',
        'strasse',
        'geburtsdatum',
        'solvenz',
        [
            'attribute' => 'bankverbindung.id',
            'label' => Yii::t('app', 'Bankverbindung'),
        ],
        'angelegt_am',
        'aktualisiert_am',
        'angelegt_von',
        [
            'attribute' => 'aktualisiertVon.id',
            'label' => Yii::t('app', 'Aktualisiert Von'),
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
if($providerAdminbesichtigungkunde->totalCount){
    $gridColumnAdminbesichtigungkunde = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'besichtigungstermin.id',
                'label' => Yii::t('app', 'Besichtigungstermin')
            ],
            [
                'attribute' => 'admin.id',
                'label' => Yii::t('app', 'Admin')
            ],
                ];
    echo Gridview::widget([
        'dataProvider' => $providerAdminbesichtigungkunde,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-adminbesichtigungkunde']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Adminbesichtigungkunde')),
        ],
        'columns' => $gridColumnAdminbesichtigungkunde
    ]);
}
?>

    </div>
    
    <div class="row">
<?php
if($providerEDateianhang->totalCount){
    $gridColumnEDateianhang = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            [
                'attribute' => 'immobilien.id',
                'label' => Yii::t('app', 'Immobilien')
            ],
            [
                'attribute' => 'user.id',
                'label' => Yii::t('app', 'User')
            ],
                ];
    echo Gridview::widget([
        'dataProvider' => $providerEDateianhang,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-e-dateianhang']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'E Dateianhang')),
        ],
        'columns' => $gridColumnEDateianhang
    ]);
}
?>

    </div>
    <div class="row">
        <h4>Bankverbindung<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnBankverbindung = [
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
            'label' => Yii::t('app', 'Aktualisiert Von'),
        ],
    ];
    echo DetailView::widget([
        'model' => $model->bankverbindung,
        'attributes' => $gridColumnBankverbindung    ]);
    ?>
    <div class="row">
        <h4>User<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnUser = [
        ['attribute' => 'id', 'visible' => false],
        'username',
        'auth_key',
        'password_hash',
        'password_reset_token',
        'email',
        'telefon',
        'status',
        'created_at',
        'updated_at',
    ];
    echo DetailView::widget([
        'model' => $model->aktualisiertVon,
        'attributes' => $gridColumnUser    ]);
    ?>
    
    <div class="row">
<?php
if($providerKundeimmobillie->totalCount){
    $gridColumnKundeimmobillie = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
                        [
                'attribute' => 'immobilien.id',
                'label' => Yii::t('app', 'Immobilien')
            ],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerKundeimmobillie,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-kundeimmobillie']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Kundeimmobillie')),
        ],
        'columns' => $gridColumnKundeimmobillie
    ]);
}
?>

    </div>
</div>
