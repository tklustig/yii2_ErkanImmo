<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\LDateianhangArt as BaseLDateianhangArt;

class LDateianhangArt extends BaseLDateianhangArt {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['bezeichnung'], 'required'],
            [['bezeichnung'], 'string', 'max' => 255]
        ]);
    }

}
