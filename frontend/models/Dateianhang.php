<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\Dateianhang as BaseDateianhang;

class Dateianhang extends BaseDateianhang {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['angelegt_am', 'aktualisert_am'], 'safe'],
            [['angelegt_von', 'aktualisiert_von', 'l_dateianhang_art_id', 'e_dateianhang_id'], 'integer'],
            [['dateiname', 'l_dateianhang_art_id', 'e_dateianhang_id'], 'required', 'except' => 'create_Dateianhang'],
            [['bezeichnung', 'dateiname'], 'string'],
            [['attachement'], 'file', 'skipOnEmpty' => true, 'maxSize' => 5 * 1024000, 'tooBig' => 'Maximal erlaubte Dateigröße:5 MByte', 'maxFiles' => 10],
        ]);
    }

}
