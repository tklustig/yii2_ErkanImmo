<?php

namespace backend\models;

use Yii;
use \backend\models\base\Bankverbindung as BaseBankverbindung;

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
            [['art', 'iban'], 'required'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['angelegt_von', 'aktualisiert_von'], 'integer'],
            [['art', 'iban', 'bic'], 'string', 'max' => 32]
        ]);
    }
	
}
