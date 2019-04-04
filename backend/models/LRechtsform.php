<?php

namespace backend\models;

use Yii;
use \backend\models\base\LRechtsform as BaseLRechtsform;

/**
 * This is the model class for table "l_rechtsform".
 */
class LRechtsform extends BaseLRechtsform
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['typus'], 'required'],
            [['typus'], 'string', 'max' => 32]
        ]);
    }
	
}
