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
            [['l_plz_id', 'l_stadt_id', 'strasse', 'bankverbindung_id'], 'required'],
            [['l_plz_id', 'l_stadt_id', 'bankverbindung_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['strasse'], 'string', 'max' => 44],
            [['solvenz'], 'string', 'max' => 1]
        ]);
    }
	
}
