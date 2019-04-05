<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => 'Mail',]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mail'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view',]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="mail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'mailFrom' => $mailFrom
    ])
    ?>

</div>
