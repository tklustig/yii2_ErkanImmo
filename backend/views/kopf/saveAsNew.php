<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Kopf */

$this->title = Yii::t('app', 'Save As New {modelClass}: ', [
    'modelClass' => 'Kopf',
]). ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kopf'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Save As New');
?>
<div class="kopf-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
