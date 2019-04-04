<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Firmendaten erstellen');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Firma'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firma-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
