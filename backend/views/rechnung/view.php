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
            <h2><?= Yii::t('app', 'Rechnung') . ' ' . Html::encode($this->title) ?></h2>
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
            <?= Html::a(Yii::t('app', 'zur Übersicht'), ['/rechnung/index'], ['class' => 'btn btn-primary ']) ?>  
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn = [
            'id',
            'datumerstellung',
            'datumfaellig',
            'rechnungPlain:html',
            'geldbetrag',
            [
                'attribute' => 'mwst.satz',
                'label' => Yii::t('app', 'Mwst(in %)'),
            ],
            [
                'attribute' => 'kunde.nachname',
                'label' => Yii::t('app', 'Kunde'),
            ],
            [
                'attribute' => 'makler.username',
                'label' => Yii::t('app', 'Makler'),
            ],
            [
                'attribute' => 'kopf.id',
                'label' => Yii::t('app', 'Kopf'),
            ],
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
        <h4>Kopf<?= ' ' . Html::encode($this->title) ?></h4>
    </div>
    <?php
    $gridColumnKopf = [
        'id',
        'data',
        'user.username',
    ];
    echo DetailView::widget([
        'model' => $model->kopf,
        'attributes' => $gridColumnKopf]);
    ?>
    <div class="row">
        <h4>Kunde<?= ' ' . Html::encode($this->title) ?></h4>
    </div>
    <?php
    $gridColumnKunde = [
        'id',
        'lPlz.plz',
        'geschlecht0.typus',
        'vorname',
        'nachname',
        'stadt',
        'strasse',
        'geburtsdatum',
        'solvenz',
        'telefon',
        'email',
        'bankverbindung.institut',
        'angelegt_am',
        'aktualisiert_am',
        [
            'attribute' => 'angelegt_von',
            'label' => Yii::t('app', 'Angelegt von'),
            'value' => function($model, $id) {
                return $model->vorname . ' ' . $model->nachname;
            },
        ],
    ];
    echo DetailView::widget([
        'model' => $model->kunde,
        'attributes' => $gridColumnKunde]);
    ?>
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
        'model' => $model->makler,
        'attributes' => $gridColumnUser]);
    ?>
    <div class="row">
        <h4>LMwst<?= ' ' . Html::encode($this->title) ?></h4>
    </div>
    <?php
    $gridColumnLMwst = [
        'id',
        'satz',
    ];
    echo DetailView::widget([
        'model' => $model->mwst,
        'attributes' => $gridColumnLMwst]);
    ?>
</div>
