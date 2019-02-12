<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Bankverbindung */

$this->title = Yii::t('app', 'Create Bankverbindung');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bankverbindung'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$string = 'Bankdaten eingeben';
$kundenname= frontend\models\Kunde::findOne(['id'=>$id])->nachname;
?>
<div class="bankverbindung-create">

    <center> <h2><?= $string ?></h2></center>
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
