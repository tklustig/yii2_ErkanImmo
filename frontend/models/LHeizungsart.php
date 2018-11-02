<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\LHeizungsart as BaseLHeizungsart;

/**
 * This is the model class for table "l_heizungsart".
 */
class LHeizungsart extends BaseLHeizungsart
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
