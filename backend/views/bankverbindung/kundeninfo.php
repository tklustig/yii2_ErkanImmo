<style>
    h3 {
        text-decoration: underline;
    }
</style>
<h3>Kundeninfo</h3>
<?php
//Diese Vorgehensweise verstöst gegen das MCV Pattern. Eigentlich müssten folgende Daten vom Controller kommen...
$expression = new yii\db\Expression('NOW()');
$now = (new \yii\db\Query)->select($expression)->scalar();
$model = \frontend\models\Kunde::findOne(['id' => $id]);
$plzId = \frontend\models\Kunde::findOne(['id' => $id])->l_plz_id;
$plz = \frontend\models\LPlz::findOne(['id' => $plzId])->plz;
$diff = strtotime($now) - strtotime($model->geburtsdatum);
$hours = floor($diff / (60 * 60));
$year = floor($hours / 24 / 365);
$output = 'geboren am ' . date("d.m.Y", strtotime($model->geburtsdatum)) . ' => ' . $year . " Jahre alt";
if (!is_numeric($model->solvenz))
    $solvenz = "keine Angaben vorhanden";
else if ($model->solvenz == true)
    $solvenz = "Kunde ist solvent und liquide";
else if ($model->solvenz == false)
    $solvenz = "Kunde ist insolvent und aliquide";
?>
<ul>
    <li><?= $model->geschlecht . ' ' . $model->vorname . ' ' . $model->nachname ?></li>
    <li>wohnhaft in <?= $plz . ' ' . $model->stadt . ', ' . $model->strasse ?></li>
    <li> <?= $output ?></li>
    <li>Zahlungskräfigkeit: <?= $solvenz ?></li>
</ul>

