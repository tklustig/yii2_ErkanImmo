<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Besichtigungstermin */

$this->title = Yii::t('app', 'Update Besichtigungstermin: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Besichtigungstermins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="besichtigungstermin-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
