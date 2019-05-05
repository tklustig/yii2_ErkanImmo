<?php

namespace common\classes;

use Yii;
use kartik\widgets\Alert;
use yii\helpers\Html;

final class error_handling {

    public static function error($fehler, $go, $id) {
        ?><?=
        Html::a(Yii::t('app', 'zurück zur View'), [$go, 'id' => $id], ['class' => 'btn btn-danger']);
        ?><br><br><?=
        Alert::widget([
            'type' => Alert::TYPE_DANGER,
            'title' => 'Konfigurationsfehler',
            'icon' => 'glyphicon glyphicon-remove-sign',
            'body' => 'Beim Auslesen der Datenbank ist ein schwerer Fehler aufgetreten! Diese Meldung entstammt der Klasse ' . get_class() . '. U.u. sind Konfiguarationsparameter in Ihrer Datenbank inkorrekt. Informieren Sie unverzüglich Ihren Admin oder den Hersteller dieser Applikation unter Angabe folgender Meldungen:<br><br>' . 'Fehlercode: ' . $fehler->getCode() . '<br>Fehlerart: ' . $fehler->getMessage() . '<br>in Zeile: ' . $fehler->getLine() . '<br>in Datei: ' . $fehler->getFile(),
            'showSeparator' => true,
            'delay' => false
        ]);
        die();
    }

    public static function error_without_id($fehler, $go) {
        ?><?=
        Html::a(Yii::t('app', 'zurück zur View'), [$go], ['class' => 'btn btn-danger']);
        ?><br><br><?=
        Alert::widget([
            'type' => Alert::TYPE_DANGER,
            'title' => 'Konfigurationsfehler',
            'icon' => 'glyphicon glyphicon-remove-sign',
            'body' => 'Beim Auslesen der Datenbank ist ein schwerer Fehler aufgetreten! Diese Meldung entstammt der Klasse ' . get_class() . '. U.u. sind Konfiguarationsparameter in Ihrer Datenbank inkorrekt. Informieren Sie unverzüglich Ihren Admin oder den Hersteller dieser Applikation unter Angabe folgender Meldungen:<br><br>' . 'Fehlercode: ' . $fehler->getCode() . '<br>Fehlerart: ' . $fehler->getMessage() . '<br>in Zeile: ' . $fehler->getLine() . '<br>in Datei: ' . $fehler->getFile(),
            'showSeparator' => true,
            'delay' => false
        ]);
        die();
    }

}
