<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Firma */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Firma',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Firma'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="firma-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
