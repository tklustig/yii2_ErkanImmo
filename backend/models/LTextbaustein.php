<?php

namespace backend\models;

use Yii;
use \backend\models\base\LTextbaustein as BaseLTextbaustein;

class LTextbaustein extends BaseLTextbaustein {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['beschreibung', 'data'], 'required'],
            [['data'], 'string'],
            [['beschreibung'], 'string', 'max' => 64]
        ]);
    }

}
