<?php

use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Besichtigungstermins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="besichtigungstermin-update">

    <?= $this->render('_formUpdate', [
        'model' => $model,
        'id'=>$id,
        'modelKunde'=>$modelKunde
    ]) ?>

</div>
