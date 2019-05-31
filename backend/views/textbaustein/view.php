<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'L Textbaustein'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ltextbaustein-view">

    <div class="row">
        <div class="col-sm-8">
            <h2><?= Yii::t('app', 'Mailserver') . ' ' . Html::encode($this->title) ?></h2>
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
            <?= Html::a(Yii::t('app', 'zur Übersicht'), ['/textbaustein/index'], ['class' => 'btn btn-primary ']) ?>  
        </div>
    </div>

    <div class="row">
        <?php
        $gridColumn = [
            'id',
            'beschreibung',
            'data:ntext',
        ];
        echo DetailView::widget([
            'model' => $model,
            'attributes' => $gridColumn
        ]);
        ?>
    </div>

    <div class="row">
        <?php
        if ($providerMail->totalCount) {
            $gridColumnMail = [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                [
                    'attribute' => 'mailserver.id',
                    'label' => Yii::t('app', 'Id Mailserver')
                ],
                'mail_from',
                'mail_to',
                'mail_cc',
                'mail_bcc',
                'betreff',
                'bodytext:ntext',
                'vorlage:ntext',
                'angelegt_am',
                [
                    'attribute' => 'angelegtVon.id',
                    'label' => Yii::t('app', 'Angelegt Von')
                ],
                'aktualisiert_am',
                [
                    'attribute' => 'aktualisiertVon.id',
                    'label' => Yii::t('app', 'Aktualisiert Von')
                ],
            ];
            echo Gridview::widget([
                'dataProvider' => $providerMail,
                'pjax' => true,
                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-mail']],
                'panel' => [
                    'type' => GridView::TYPE_PRIMARY,
                    'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode(Yii::t('app', 'Mail')),
                ],
                'columns' => $gridColumnMail
            ]);
        }
        ?>

    </div>
</div>
