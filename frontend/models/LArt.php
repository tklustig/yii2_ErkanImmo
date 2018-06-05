<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\LArt as BaseLArt;

/**
 * This is the model class for table "l_art".
 */
class LArt extends BaseLArt
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['bezeichnung'], 'required'],
            [['bezeichnung'], 'string', 'max' => 32]
        ]);
    }
	
}
