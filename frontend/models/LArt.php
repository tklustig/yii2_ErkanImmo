<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\LArt as BaseLArt;

class LArt extends BaseLArt {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['bezeichnung'], 'required'],
            [['bezeichnung'], 'string', 'max' => 32]
        ]);
    }

}
