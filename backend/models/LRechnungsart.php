<?php

namespace backend\models;

use Yii;
use \backend\models\base\LRechnungsart as BaseLRechnungsart;

class LRechnungsart extends BaseLRechnungsart
{

    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['data', 'art'], 'required'],
            [['data'], 'string'],
            [['art'], 'string', 'max' => 32],
        ]);
    }
	
}
