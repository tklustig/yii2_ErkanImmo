<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Bankverbindung */

$this->title = Yii::t('app', 'Create Bankverbindung');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bankverbindung'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bankverbindung-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
