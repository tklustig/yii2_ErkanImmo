<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\Immobilien as BaseImmobilien;

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
            [['id_bild', 'wohnflaeche', 'raeume', 'k_grundstuecksgroesse', 'l_plz_id', 'user_id', 'l_art_id', 'l_heizungsart_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['bezeichnung', 'sonstiges'], 'string'],
            [['strasse', 'wohnflaeche', 'raeume', 'geldbetrag', 'l_plz_id', 'stadt', 'user_id', 'l_art_id'], 'required'],
            [['geldbetrag', 'k_provision', 'v_nebenkosten'], 'number'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['strasse'], 'string', 'max' => 45],
            [['balkon_vorhanden', 'fahrstuhl_vorhanden'], 'string', 'max' => 1],
            [['stadt'], 'string', 'max' => 255]
        ]);
    }
	
}
