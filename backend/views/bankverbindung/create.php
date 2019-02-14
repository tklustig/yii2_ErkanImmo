<?php

use frontend\models\Kunde;

$this->title = Yii::t('app', 'Create Bankverbindung');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bankverbindung'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$vornameKunde = Kunde::findOne(['id' => $id])->vorname;
$nachnameKunde = Kunde::findOne(['id' => $id])->nachname;
$string = "Bankdaten für $nachnameKunde, $vornameKunde eingeben";
?>
<div class="bankverbindung-create">
    <center><h2><?= $string ?></h2>
        <p>Die von der Applikation ermittelten Werte müssen von Ihnen nach Ihrem Request bestätigt werden, bevor sie in die Datenbank gespeichert werden.</p>
    </center>
    <?=
    $this->render('_form', [
        'model' => $model,
        'id'=>$id
    ])
    ?>
</div>
