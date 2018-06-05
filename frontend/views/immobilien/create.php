<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Immobilien */

$this->title = Yii::t('app', 'Create Immobilien');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Immobilien'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="immobilien-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
