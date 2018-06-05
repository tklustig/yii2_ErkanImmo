<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\Dateianhang as BaseDateianhang;

/**
 * This is the model class for table "dateianhang".
 */
class Dateianhang extends BaseDateianhang
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['dateiname', 'angelegt_von', 'aktualisiert_von', 'l_dateianhang_art_id', 'e_dateianhang_id'], 'required'],
            [['angelegt_am', 'aktualisert_am'], 'safe'],
            [['angelegt_von', 'aktualisiert_von', 'l_dateianhang_art_id', 'e_dateianhang_id'], 'integer'],
            [['bezeichnung', 'dateiname'], 'string', 'max' => 255]
        ]);
    }
	
}
