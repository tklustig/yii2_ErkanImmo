<?php

namespace backend\models;

use Yii;
use \backend\models\base\LLaenderkennung as BaseLLaenderkennung;

class LLaenderkennung extends BaseLLaenderkennung {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['code', 'es', 'fr', 'it', 'ru'], 'required'],
            [['code'], 'string', 'max' => 2],
            [['en', 'de', 'es', 'fr', 'it', 'ru'], 'string', 'max' => 100]
        ]);
    }

}
