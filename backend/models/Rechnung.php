<?php

namespace backend\models;

use Yii;
use \backend\models\base\Rechnung as BaseRechnung;

class Rechnung extends BaseRechnung
{

    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['datumerstellung', 'datumfaellig', 'geldbetrag', 'kunde_id', 'makler_id'], 'required'],
            [['datumerstellung', 'datumfaellig', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['beschreibung'], 'string'],
            [['geldbetrag'], 'number'],
            [['mwst_id', 'kunde_id', 'makler_id', 'kopf_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['aktualisiert_von'], 'unique']
        ]);
    }
	
}
