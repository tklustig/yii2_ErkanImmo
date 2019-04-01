<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Rechnungsart erstellen');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lrechnungsart-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
