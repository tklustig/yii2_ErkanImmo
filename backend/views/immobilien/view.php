<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Immobilien */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Immobilien'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="immobilien-view">

    <div class="row">
        <div class="col-sm-8">
            <h2><?= Yii::t('app', 'Immobilien').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-4" style="margin-top: 15px">
<?=             
             Html::a('<i class="fa glyphicon glyphicon-hand-up"></i> ' . Yii::t('app', 'PDF'), 
                ['pdf', 'id' => $model->id, 'l_plz_id' => $model->l_plz_id, 'l_stadt_id' => $model->l_stadt_id, 'user_id' => $model->user_id, 'l_art_id' => $model->l_art_id],
                [
                    'class' => 'btn btn-danger',
                    'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => Yii::t('app', 'Will open the generated PDF file in a new window')
                ]
            )?>
            <?= Html::a(Yii::t('app', 'Save As New'), ['save-as-new', 'id' => $model->id, 'l_plz_id' => $model->l_plz_id, 'l_stadt_id' => $model->l_stadt_id, 'user_id' => $model->user_id, 'l_art_id' => $model->l_art_id], ['class' => 'btn btn-info']) ?>            
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id, 'l_plz_id' => $model->l_plz_id, 'l_stadt_id' => $model->l_stadt_id, 'user_id' => $model->user_id, 'l_art_id' => $model->l_art_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id, 'l_plz_id' => $model->l_plz_id, 'l_stadt_id' => $model->l_stadt_id, 'user_id' => $model->user_id, 'l_art_id' => $model->l_art_id], [
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
        'bezeichnung:ntext',
        'strasse',
        'wohnflaeche',
        'raeume',
        'geldbetrag',
        'l_plz_id',
        [
            'attribute' => 'lStadt.id',
            'label' => Yii::t('app', 'L Stadt'),
        ],
        [
            'attribute' => 'user.id',
            'label' => Yii::t('app', 'User'),
        ],
        [
            'attribute' => 'lArt.id',
            'label' => Yii::t('app', 'L Art'),
        ],
        'angelegt_am',
        'aktualisiert_am',
        [
            'attribute' => 'angelegtVon.id',
            'label' => Yii::t('app', 'Angelegt Von'),
        ],
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
if($providerBesichtigungstermin->totalCount){
    $gridColumnBesichtigungstermin = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'visible' => false],
            'l_plz_id',
            'strasse',
            'uhrzeit',
            'Relevanz',
            [
                'attribute' => 'lStadt.id',
                'label' => Yii::t('app', 'L Stadt')
            ],
            'angelegt_am',
            'aktualisiert_am',
            [
                'attribute' => 'angelegtVon.id',
                'label' => Yii::t('app', 'Angelegt Von')
            ],
            [
                'attribute' => 'aktualisiertVon.id',
                'label' => Yii::t('app', 'Aktualisiert Von')
            ],
                ];
    echo Gridview::widget([
        'dataProvider' => $providerBesichtigungstermin,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-besichtigungstermin']],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Besichtigungstermin')),
        ],
        'columns' => $gridColumnBesichtigungstermin
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
                'attribute' => 'user.id',
                'label' => Yii::t('app', 'User')
            ],
            [
                'attribute' => 'kunde.id',
                'label' => Yii::t('app', 'Kunde')
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
        <h4>LArt<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnLArt = [
        ['attribute' => 'id', 'visible' => false],
        'bezeichnung:ntext',
    ];
    echo DetailView::widget([
        'model' => $model->lArt,
        'attributes' => $gridColumnLArt    ]);
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
        'model' => $model->user,
        'attributes' => $gridColumnUser    ]);
    ?>
    <div class="row">
        <h4>LStadt<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnLStadt = [
        ['attribute' => 'id', 'visible' => false],
        'stadt',
    ];
    echo DetailView::widget([
        'model' => $model->lStadt,
        'attributes' => $gridColumnLStadt    ]);
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
        'model' => $model->angelegtVon,
        'attributes' => $gridColumnUser    ]);
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
                'attribute' => 'kunde.id',
                'label' => Yii::t('app', 'Kunde')
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
