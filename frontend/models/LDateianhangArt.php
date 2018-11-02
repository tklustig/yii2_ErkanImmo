<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\LDateianhangArt as BaseLDateianhangArt;

/**
 * This is the model class for table "l_dateianhang_art".
 */
class LDateianhangArt extends BaseLDateianhangArt
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['bezeichnung'], 'required'],
            [['bezeichnung'], 'string', 'max' => 255]
        ]);
    }
	
}
