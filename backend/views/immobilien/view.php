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
<div class='container'>
    <div class='jumbotron'>
        <div class="row">
            <div class="col-sm-8">
                <h2><?= Yii::t('app', 'Bewerber') . ' ' . Html::encode($this->title) ?></h2>
            </div>
            <div class="upper" style="margin-top: 15px">
                <?= Html::a(Yii::t('app', 'zur Übersicht'), ['index', 'id' => $model->id], ['class' => 'btn btn-success ']) ?>
                <?=
                Html::a('<i class="fa glyphicon glyphicon-hand-up"></i> ' . Yii::t('app', 'PDF'), ['pdf', 'id' => $model->id, 'l_plz_id' => $model->l_plz_id, 'user_id' => $model->user_id, 'l_art_id' => $model->l_art_id], [
                    'class' => 'btn btn-danger',
                    'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => Yii::t('app', 'Will open the generated PDF file in a new window')
                        ]
                )
                ?>
            </div>
        </div>

        <div class="row">
            <?php
            $gridColumn = [
                ['attribute' => 'id', 'visible' => false],
                'bezeichnung:html',
                'strasse',
                'wohnflaeche',
                'raeume',
                'geldbetrag',
                'l_plz_id',
                [
                    'attribute' => 'stadt',
                    'label' => Yii::t('app', 'Stadt'),
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
            if ($providerBesichtigungstermin->totalCount) {
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
            ?>

        </div>
        <div class="row">
            <h4>LArt<?= ' ' . Html::encode($this->title) ?></h4>
        </div>
        <?php
        $gridColumnLArt = [
            ['attribute' => 'id', 'visible' => false],
            'bezeichnung:html',
        ];
        echo DetailView::widget([
            'model' => $model->lArt,
            'attributes' => $gridColumnLArt]);
        ?>
        <div class="row">
            <h4>User<?= ' ' . Html::encode($this->title) ?></h4>
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
            if ($providerKundeimmobillie->totalCount) {
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
</div>

