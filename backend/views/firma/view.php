<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Firma */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Firma'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firma-view">

    <div class="row">
        <div class="col-sm-8">
            <h2><?= Yii::t('app', 'Firma') . ' ' . Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-4" style="margin-top: 15px">
            <?=
            Html::a('<i class="fa glyphicon glyphicon-hand-up"></i> ' . Yii::t('app', 'PDF'), ['pdf', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'target' => '_blank',
                'data-toggle' => 'tooltip',
                'title' => Yii::t('app', 'Will open the generated PDF file in a new window')
                    ]
            )
            ?>
            <?= Html::a(Yii::t('app', 'zur Übersicht'), ['/firma/index'], ['class' => 'btn btn-primary ']) ?>  
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn = [
            'id',
            'firmenname',
            [
                'attribute' => 'lRechtsform.typus',
                'label' => Yii::t('app', 'Rechtsform'),
            ],
            'strasse',
            'hausnummer',
            [
                'attribute' => 'lPlz.plz',
                'label' => Yii::t('app', 'Plz'),
            ],
            'ort',
            'geschaeftsfuehrer',
            'prokurist',
            'umsatzsteuerID',
            'bankdaten:html',
            'anzahlMitarbeiter',
            [
                'attribute' => 'angelegtVon.username',
                'label' => Yii::t('app', 'Angelegt Von'),
            ],
            [
                'attribute' => 'aktualisiertVon.username',
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
        <h4>User<?= ' ' . Html::encode($this->title) ?></h4>
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
        'attributes' => $gridColumnUser]);
    ?>
    <div class="row">
        <h4>LPlz<?= ' ' . Html::encode($this->title) ?></h4>
    </div>
    <?php
    $gridColumnLPlz = [
        'id',
        'plz',
        'ort',
        'bl',
    ];
    echo DetailView::widget([
        'model' => $model->lPlz,
        'attributes' => $gridColumnLPlz]);
    ?>
</div>
