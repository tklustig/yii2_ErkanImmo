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
            [['id_bild', 'wohnflaeche', 'raeume', 'l_plz_id', 'l_stadt_id', 'user_id', 'l_art_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['bezeichnung'], 'string'],
            [['strasse', 'wohnflaeche', 'raeume', 'geldbetrag', 'l_plz_id', 'l_stadt_id', 'user_id', 'l_art_id'], 'required'],
            [['geldbetrag'], 'number'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['strasse'], 'string', 'max' => 45]
        ]);
    }
	
}
