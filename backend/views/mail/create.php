<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Mail */

$this->title = Yii::t('app', 'Mail erstellen');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mail'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
