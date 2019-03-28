<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Kopf */

$this->title = Yii::t('app', 'Create Kopf');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Kopf'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kopf-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
