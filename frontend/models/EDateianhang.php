<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\EDateianhang as BaseEDateianhang;

class EDateianhang extends BaseEDateianhang {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['immobilien_id', 'user_id', 'kunde_id'], 'integer']
        ]);
    }

}
