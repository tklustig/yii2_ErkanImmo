<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\LTextbaustein */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'L Textbaustein'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ltextbaustein-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'L Textbaustein').' '. Html::encode($this->title) ?></h2>
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
if($providerMail->totalCount){
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
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => Html::encode(Yii::t('app', 'Mail')),
        ],
        'panelHeadingTemplate' => '<h4>{heading}</h4>{summary}',
        'toggleData' => false,
        'columns' => $gridColumnMail
    ]);
}
?>
    </div>
</div>
