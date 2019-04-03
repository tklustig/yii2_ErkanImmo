<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\LBegriffe */

$this->title = Yii::t('app', 'Create L Begriffe');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'L Begriffe'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lbegriffe-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
