<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Besichtigungstermin */

$this->title = Yii::t('app', 'Create Besichtigungstermin');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Besichtigungstermins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="besichtigungstermin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
