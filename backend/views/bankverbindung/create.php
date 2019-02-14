<?php

use yii\helpers\Html;
use frontend\models\Kunde;

$this->title = Yii::t('app', 'Create Bankverbindung');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bankverbindung'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$vornameKunde = Kunde::findOne(['id' => $id])->vorname;
$nachnameKunde = Kunde::findOne(['id' => $id])->nachname;
$string = "Bankdaten für $nachnameKunde, $vornameKunde eingeben";
$search = "$('.search-button').click(function(){
	$('.search-form').toggle(1000);
	return false;
});";
$this->registerJs($search);
?>
<div class="bankverbindung-create">
    <center><h2><?= $string ?></h2>
        <p>Die von der Applikation ermittelten Werte müssen von Ihnen nach Ihrem Request bestätigt werden, bevor sie in die Datenbank gespeichert werden.</p>
    </center>
    <div style="float:right;">
        <p>
<?= Html::a(Yii::t('app', 'Kundendaten'), '#', ['class' => 'btn btn-info search-button']) ?>
        </p>
    </div>
    <div class="search-form" style="display:none">
    <?= $this->render('kundeninfo', ['id' => $id]); ?>
    </div>
    <?=
    $this->render('_form', [
        'model' => $model,
        'id' => $id
    ])
    ?>
</div>
