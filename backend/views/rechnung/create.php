<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Rechnung */

$this->title = Yii::t('app', 'Create Rechnung');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Rechnung'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rechnung-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
