<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kopf'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kopf-view">

    <div class="row">
        <div class="col-sm-8">
            <h2><?= Yii::t('app', 'Kopf') . ' ' . Html::encode($this->title) ?></h2>
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
            <?= Html::a(Yii::t('app', 'zur Übersicht'), ['/kopf/index'], ['class' => 'btn btn-primary ']) ?>  
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn = [
            ['attribute' => 'id', 'visible' => false],
            'data:ntext',
            [
                'attribute' => 'user.username',
                'label' => Yii::t('app', 'Makler'),
            ],
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>
    <div class="row">
        <h4>Makler<?= ' ' . Html::encode($this->title) ?></h4>
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
        'attributes' => $gridColumnUser]);
    ?>

    <div class="row">
        <?php
        if ($providerRechnung->totalCount) {
            $gridColumnRechnung = [
                ['class' => 'yii\grid\SerialColumn'],
                ['attribute' => 'id', 'visible' => false],
                'datumerstellung',
                'datumfaellig',
                'beschreibung:ntext',
                'geldbetrag',
                [
                    'attribute' => 'mwst.id',
                    'label' => Yii::t('app', 'Mwst')
                ],
                [
                    'attribute' => 'kunde.id',
                    'label' => Yii::t('app', 'Kunde')
                ],
                [
                    'attribute' => 'makler.id',
                    'label' => Yii::t('app', 'Makler')
                ],
                [
                    'attribute' => 'angelegtVon.id',
                    'label' => Yii::t('app', 'Angelegt Von')
                ],
                [
                    'attribute' => 'aktualisiertVon.id',
                    'label' => Yii::t('app', 'Aktualisiert Von')
                ],
                'angelegt_am',
                'aktualisiert_am',
            ];
            echo Gridview::widget([
                'dataProvider' => $providerRechnung,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-rechnung']],
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Rechnung')),
                ],
                'columns' => $gridColumnRechnung
            ]);
        }
        ?>

    </div>
</div>
