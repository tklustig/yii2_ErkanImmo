<?php

use yii\helpers\Html;

$this->title = $name;
?>
<?php
$mailTo = common\models\User::findOne(Yii::$app->user->identity->id)->email;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Obiger Fehler ist aufgrund eines Programmierfehlers aufgetreten. Vermutlich wurde ein falscher Parameter an die Datenbank übergeben.<br>
        Da im Produktionsmodus der Debugmodus deaktivert wurde, da außerdem im Code diese Exception nicht erwartet bzw. abgefangen wurde, bekommen
        Sie diese Seite zu sehen.
    </p>
    <p>
        Bitte <a href="mailto:<?= $mailTo ?>?subject=<?= $name ?>">wenden</a> sie sich an den Softwarehersteller dieser Applikation und teilen sie ihm folgenden Fehlercode mit:<strong>aGX5280:<?= $name ?></strong>
    </p>
</div>


