<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\EDateianhang as BaseEDateianhang;

/**
 * This is the model class for table "e_dateianhang".
 */
class EDateianhang extends BaseEDateianhang
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['immobilien_id', 'user_id', 'kunde_id'], 'integer']
        ]);
    }
	
}
