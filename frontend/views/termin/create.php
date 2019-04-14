<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Create Besichtigungstermin');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Besichtigungstermins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="besichtigungstermin-create">
    <?=
    $this->render('_form', [
        'model' => $model,
        'modelKunde' => $modelKunde,
        'id' => $id,
    ])
    ?>

</div>
