<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\LHeizungsart as BaseLHeizungsart;

class LHeizungsart extends BaseLHeizungsart {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['bezeichnung'], 'required'],
            [['bezeichnung'], 'string', 'max' => 255]
        ]);
    }

}
