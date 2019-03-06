<?php

namespace backend\models;

use Yii;
use \backend\models\base\LGeschlecht as BaseLGeschlecht;

class LGeschlecht extends BaseLGeschlecht {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['typus'], 'required'],
            [['typus'], 'string', 'max' => 16]
        ]);
    }

}
