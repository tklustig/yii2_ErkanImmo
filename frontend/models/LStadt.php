<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\LStadt as BaseLStadt;

/**
 * This is the model class for table "l_stadt".
 */
class LStadt extends BaseLStadt
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['stadt'], 'string', 'max' => 255]
        ]);
    }
	
}
