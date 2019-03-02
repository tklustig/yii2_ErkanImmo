<?php

namespace backend\models;

use Yii;
use \backend\models\base\Bankverbindung as BaseBankverbindung;

/**
 * This is the model class for table "bankverbindung".
 */
class Bankverbindung extends BaseBankverbindung {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['laenderkennung', 'blz', 'kontoNr'], 'required', 'except' => 'create_Bankverbindung'],
            [['laenderkennung', 'blz', 'kontoNr'], 'safe', 'on' => 'create_Bankverbindung'],
            [['blz', 'kontoNr', 'kunde_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['laenderkennung'], 'string', 'max' => 3],
            [['institut'], 'string', 'max' => 255],
            [['iban'], 'string', 'max' => 32],
            [['bic'], 'string', 'max' => 16],
        ]);
    }

}
