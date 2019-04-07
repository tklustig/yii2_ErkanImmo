<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Mail */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mail'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Mail') . ' ' . Html::encode($this->title) ?></h2>
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
            <?= Html::a(Yii::t('app', 'zur Übersicht'), ['/site/index'], ['class' => 'btn btn-primary ']) ?>  
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn = [
            'id',
            [
                'attribute' => 'mailserver.serverHost',
                'label' => Yii::t('app', 'Id Mailserver'),
            ],
            'mail_from',
            'mail_to',
            'mail_cc',
            'mail_bcc',
            'betreff',
            'bodytext:html',
            'angelegt_am',
            [
                'attribute' => 'angelegtVon.username',
                'label' => Yii::t('app', 'Angelegt von'),
            ],
            'aktualisiert_am',
            [
                'attribute' => 'aktualisiertVon.username',
                'label' => Yii::t('app', 'Aktualisiert von'),
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
    <div class="row">
        <h4>Mailserver<?= ' ' . Html::encode($this->title) ?></h4>
    </div>
    <?php
    $gridColumnMailserver = [
        'id',
        'serverURL',
        'serverHost',
        'username',
        'port',
        [
            'attribute' => 'useEncryption',
            'format' => 'raw',
            'value' => $model->mailserver->useEncryption ? '<span class="label label-success">Ja</span>' : '<span class="label label-danger">Nein</span>',
            'widgetOptions' => [
                'pluginOptions' => [
                    'onText' => 'Ja',
                    'offText' => 'Nein',
                ]
            ],
            'valueColOptions' => ['style' => 'width:30%']
        ],
        'encryption',
        [
            'attribute' => 'angelegtVon.username',
            'label' => Yii::t('app', 'Angelegt von'),
        ],
        [
            'attribute' => 'aktualisiertVon.username',
            'label' => Yii::t('app', 'Aktualisiert von'),
        ],
        'angelegt_am',
        'aktualisiert_am',
    ];
    echo DetailView::widget([
        'model' => $model->mailserver,
        'attributes' => $gridColumnMailserver]);
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
        'model' => $model->angelegtVon,
        'attributes' => $gridColumnUser]);
    ?>
</div>
