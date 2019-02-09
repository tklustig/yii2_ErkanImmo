<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\Kunde as BaseKunde;

/**
 * This is the model class for table "kunde".
 */
class Kunde extends BaseKunde
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['l_plz_id', 'vorname', 'nachname', 'stadt', 'strasse', 'geburtsdatum', 'bankverbindung_id'], 'required'],
            [['l_plz_id', 'bankverbindung_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['geburtsdatum', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['vorname', 'nachname', 'stadt'], 'string', 'max' => 255],
            [['strasse'], 'string', 'max' => 255],
            [['solvenz'], 'integer', 'max' => 1],
        ]);
    }
	
}
