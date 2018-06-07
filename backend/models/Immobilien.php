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
            [['bezeichnung', 'sonstiges'], 'string'],
            [['strasse', 'wohnflaeche', 'raeume', 'geldbetrag', 'l_plz_id', 'l_stadt_id', 'user_id', 'l_art_id'], 'required'],
            [['wohnflaeche', 'k_grundstuecksgroesse', 'raeume', 'l_plz_id', 'l_stadt_id', 'user_id', 'l_art_id', 'l_heizungsart_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['geldbetrag', 'v_nebenkosten', 'k_provision'], 'number'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['strasse'], 'string', 'max' => 45],
            [['balkon_vorhanden', 'fahrstuhl_vorhanden'], 'string', 'max' => 1]
        ]);
    }
	
}
