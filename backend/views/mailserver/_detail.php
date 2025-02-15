<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Mailserver */

?>
<div class="mailserver-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Html::encode($model->id) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'id',
        'serverURL:url',
        'serverHost',
        'username',
        'password',
        'port',
        'useEncryption',
        'encryption',
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
</div>