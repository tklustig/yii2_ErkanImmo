<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Mail erstellen');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mail'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modelDateianhang' => $modelDateianhang,
        'mailFrom' => $mailFrom
    ])
    ?>
</div>
