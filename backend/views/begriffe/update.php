<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\LBegriffe */

$this->title = Yii::t('app', 'Aktualisiere {modelClass}: ', [
    'modelClass' => 'Begriffe',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'L Begriffe'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="lbegriffe-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
