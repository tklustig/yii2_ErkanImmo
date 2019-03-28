<?php

namespace backend\models;

use Yii;
use \backend\models\base\LMwst as BaseLMwst;

class LMwst extends BaseLMwst {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['satz'], 'required'],
            [['satz'], 'number']
        ]);
    }

}
