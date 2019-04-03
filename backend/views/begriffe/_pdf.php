<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'L Begriffe'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lbegriffe-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= Yii::t('app', 'L Begriffe').' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'id',
        'typ',
        'data',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>
