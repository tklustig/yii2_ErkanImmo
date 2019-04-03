<?php

namespace backend\models;

use Yii;
use \backend\models\base\LBegriffe as BaseLBegriffe;

class LBegriffe extends BaseLBegriffe
{

    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['typ', 'data'], 'required'],
            [['typ'], 'string', 'max' => 256],
            [['data'], 'string', 'max' => 128]
        ]);
    }
	
}
