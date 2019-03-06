<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\Kundeimmobillie as BaseKundeimmobillie;

class Kundeimmobillie extends BaseKundeimmobillie {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['kunde_id', 'immobilien_id'], 'required'],
            [['kunde_id', 'immobilien_id'], 'integer']
        ]);
    }

}
