<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Mailserver */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mailserver'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailserver-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'Mailserver').' '. Html::encode($this->title) ?></h2>
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
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>
