<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\Kundeimmobillie as BaseKundeimmobillie;

/**
 * This is the model class for table "kundeimmobillie".
 */
class Kundeimmobillie extends BaseKundeimmobillie
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['kunde_id', 'immobilien_id'], 'required'],
            [['kunde_id', 'immobilien_id'], 'integer']
        ]);
    }
	
}
