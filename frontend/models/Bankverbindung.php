<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\Bankverbindung as BaseBankverbindung;

/**
 * This is the model class for table "bankverbindung".
 */
class Bankverbindung extends BaseBankverbindung
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['laenderkennung', 'blz', 'kontoNr'], 'required'],
            [['blz', 'kontoNr', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['laenderkennung'], 'string', 'max' => 3],
            [['institut'], 'string', 'max' => 255],
            [['iban'], 'string', 'max' => 32],
            [['bic'], 'string', 'max' => 8]
        ]);
    }
	
}
