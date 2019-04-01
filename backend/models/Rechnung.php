<?php

namespace backend\models;

use Yii;
use \backend\models\base\Rechnung as BaseRechnung;

/**
 * This is the model class for table "rechnung".
 */
class Rechnung extends BaseRechnung
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['datumerstellung', 'datumfaellig', 'geldbetrag', 'kunde_id', 'makler_id', 'rechnungPlain'], 'required'],
            [['datumerstellung', 'datumfaellig', 'aktualisiert_am', 'angelegt_am'], 'safe'],
            [['beschreibung', 'vorlage', 'rechnungPlain'], 'string'],
            [['geldbetrag'], 'number'],
            [['mwst_id', 'kunde_id', 'makler_id', 'kopf_id', 'rechungsart_id', 'aktualisiert_von', 'angelegt_von'], 'integer']
        ]);
    }
	
}
