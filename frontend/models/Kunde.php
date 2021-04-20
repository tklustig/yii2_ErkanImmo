<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\Kunde as BaseKunde;

class Kunde extends BaseKunde {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['geschlecht', 'vorname', 'nachname', 'strasse', 'email', 'l_plz_id'], 'required'],
            [['l_plz_id', 'angelegt_von', 'aktualisiert_von', 'geschlecht', 'bankverbindung_id'], 'integer'],
            [['geburtsdatum', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            ['email', 'email'],
            [['vorname', 'nachname', 'stadt'], 'string', 'max' => 255],
            [['strasse'], 'string', 'max' => 44],
            [['solvenz'], 'boolean'],
            ['telefon', 'filter', 'filter' => function ($value) {
                    return $value;
                }]]);
    }

}
