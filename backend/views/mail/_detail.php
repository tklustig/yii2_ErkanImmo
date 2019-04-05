<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Mail */

?>
<div class="mail-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->id) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'id',
        [
            'attribute' => 'mailserver.id',
            'label' => Yii::t('app', 'Id Mailserver'),
        ],
        'mail_from',
        'mail_to',
        'mail_cc',
        'mail_bcc',
        'betreff',
        'bodytext:ntext',
        'angelegt_am',
        [
            'attribute' => 'angelegtVon.id',
            'label' => Yii::t('app', 'Angelegt Von'),
        ],
        'aktualisiert_am',
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
</div>