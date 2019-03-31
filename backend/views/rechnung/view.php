<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Rechnung */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Rechnung'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rechnung-view">

    <div class="row">
        <div class="col-sm-8">
            <h2><?= Yii::t('app', 'Rechnung').' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-4" style="margin-top: 15px">
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
            <?= Html::a(Yii::t('app', 'Save As New'), ['save-as-new', 'id' => $model->id], ['class' => 'btn btn-info']) ?>            
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
        'id',
        'datumerstellung',
        'datumfaellig',
        'beschreibung:ntext',
        'geldbetrag',
        [
            'attribute' => 'mwst.id',
            'label' => Yii::t('app', 'Mwst'),
        ],
        [
            'attribute' => 'kunde.id',
            'label' => Yii::t('app', 'Kunde'),
        ],
        [
            'attribute' => 'makler.id',
            'label' => Yii::t('app', 'Makler'),
        ],
        [
            'attribute' => 'kopf.id',
            'label' => Yii::t('app', 'Kopf'),
        ],
        [
            'attribute' => 'angelegtVon.id',
            'label' => Yii::t('app', 'Angelegt Von'),
        ],
        [
            'attribute' => 'aktualisiertVon.id',
            'label' => Yii::t('app', 'Aktualisiert Von'),
        ],
        'angelegt_am',
        'aktualisiert_am',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]);
?>
    </div>
    <div class="row">
        <h4>Kopf<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnKopf = [
        'id',
        'data',
        'user_id',
    ];
    echo DetailView::widget([
        'model' => $model->kopf,
        'attributes' => $gridColumnKopf    ]);
    ?>
    <div class="row">
        <h4>Kunde<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnKunde = [
        'id',
        'l_plz_id',
        'geschlecht',
        'vorname',
        'nachname',
        'stadt',
        'strasse',
        'geburtsdatum',
        'solvenz',
        'telefon',
        'email',
        'bankverbindung_id',
        'angelegt_am',
        'aktualisiert_am',
        'angelegt_von',
        [
            'attribute' => 'aktualisiertVon.id',
            'label' => Yii::t('app', 'Aktualisiert Von'),
        ],
    ];
    echo DetailView::widget([
        'model' => $model->kunde,
        'attributes' => $gridColumnKunde    ]);
    ?>
    <div class="row">
        <h4>User<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnUser = [
        'id',
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
        'model' => $model->makler,
        'attributes' => $gridColumnUser    ]);
    ?>
    <div class="row">
        <h4>LMwst<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnLMwst = [
        'id',
        'satz',
    ];
    echo DetailView::widget([
        'model' => $model->mwst,
        'attributes' => $gridColumnLMwst    ]);
    ?>
    <div class="row">
        <h4>User<?= ' '. Html::encode($this->title) ?></h4>
    </div>
    <?php 
    $gridColumnUser = [
        'id',
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
        'id',
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
</div>
