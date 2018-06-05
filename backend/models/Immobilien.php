<?php

namespace backend\models;

use Yii;
use \backend\models\base\Immobilien as BaseImmobilien;

/**
 * This is the model class for table "immobilien".
 */
class Immobilien extends BaseImmobilien
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['id_bild', 'l_plz_id', 'l_stadt_id', 'user_id', 'l_art_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['bezeichnung'], 'string'],
            [['strasse', 'l_plz_id', 'l_stadt_id', 'user_id', 'l_art_id'], 'required'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['strasse'], 'string', 'max' => 45]
        ]);
    }
	
}
