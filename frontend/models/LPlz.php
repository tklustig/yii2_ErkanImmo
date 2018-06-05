<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\LPlz as BaseLPlz;

/**
 * This is the model class for table "l_plz".
 */
class LPlz extends BaseLPlz
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['plz', 'ort', 'bl'], 'required'],
            [['plz'], 'string', 'max' => 5],
            [['ort', 'bl'], 'string', 'max' => 50]
        ]);
    }
	
}
