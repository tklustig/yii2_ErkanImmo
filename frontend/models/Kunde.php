<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\Kunde as BaseKunde;

/**
 * This is the model class for table "kunde".
 */
class Kunde extends BaseKunde {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['l_plz_id', 'geschlecht', 'vorname', 'nachname', 'stadt', 'strasse'], 'required'],
            [['l_plz_id', 'bankverbindung_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['geburtsdatum', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            ['email', 'email'],
            [['geschlecht'], 'string', 'max' => 64],
            [['vorname', 'nachname', 'stadt'], 'string', 'max' => 255],
            [['strasse'], 'string', 'max' => 44],
            [['solvenz'], 'string', 'max' => 1],
            ['telefon', 'filter', 'filter' => function ($value) {
                    return $value;
                }],
        ]);
    }

}
