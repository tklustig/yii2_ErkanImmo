<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Mailserver */

$this->title = Yii::t('app', 'Create Mailserver');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mailserver'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailserver-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
