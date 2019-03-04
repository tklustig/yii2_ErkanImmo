<?php

namespace backend\models;

use Yii;
use \backend\models\base\LGeschlecht as BaseLGeschlecht;

/**
 * This is the model class for table "l_geschlecht".
 */
class LGeschlecht extends BaseLGeschlecht
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['typus'], 'required'],
            [['typus'], 'string', 'max' => 16]
        ]);
    }
	
}
