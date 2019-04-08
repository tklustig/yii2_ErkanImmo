<?php
use yii\helpers\Html;
$this->title = $name;
?>
<?php
$mailTo = 'tklustig.thomas@gmail.com';
$fehler="Fehlercode:aGX5280_$name";
?>
<div class="site-error">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>
	 <div class="alert alert-warning">
    <p>
        Obiger Fehler ist aufgrund eines Programmierfehlers aufgetreten. Vermutlich wurde ein falscher Parameter an die Datenbank übergeben.<br>
        Da im Produktionsmodus der Debugmodus deaktivert wurde, da außerdem im Code diese Exception nicht erwartet bzw. abgefangen wurde, bekommen
        Sie diese Seite zu sehen.
    </p>
    <p>
        Bitte <a href="mailto:<?= $mailTo ?>?subject=<?= $fehler ?>">wenden</a> sie sich an den Softwarehersteller dieser Applikation und teilen sie ihm folgenden Fehlercode mit:<strong>aGX5280:<?= $name ?></strong>. Der Link enthät bereits alle erforderlichen Angaben!
    </p>
	</div>
</div>




