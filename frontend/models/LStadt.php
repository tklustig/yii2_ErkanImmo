<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\LStadt as BaseLStadt;

class LStadt extends BaseLStadt {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['stadt'], 'string', 'max' => 255]
        ]);
    }

}
