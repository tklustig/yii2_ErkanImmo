<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Immobilien */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Immobilien',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Immobilien'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id, 'l_plz_id' => $model->l_plz_id, 'l_stadt_id' => $model->l_stadt_id, 'user_id' => $model->user_id, 'l_art_id' => $model->l_art_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="immobilien-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
