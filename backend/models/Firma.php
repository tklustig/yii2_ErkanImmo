<?php

namespace backend\models;

use Yii;
use \backend\models\base\Firma as BaseFirma;

class Firma extends BaseFirma
{

    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
           [['firmenname', 'l_rechtsform_id', 'strasse', 'l_plz_id', 'ort'], 'required'],
            [['l_rechtsform_id', 'hausnummer', 'l_plz_id', 'anzahlMitarbeiter', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['firmenname', 'strasse', 'ort', 'umsatzsteuerID'], 'string', 'max' => 64],
            [['geschaeftsfuehrer', 'prokurist'], 'string', 'max' => 32],
            [['bankdaten'], 'string', 'max' => 256],
            [['l_plz_id'], 'unique'],
            [['aktualisiert_von'], 'unique'],
            [['angelegt_von'], 'unique']
        ]);
    }
	
}
