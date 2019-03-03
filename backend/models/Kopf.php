<?php

namespace backend\models;

use Yii;
use \backend\models\base\Kopf as BaseKopf;

class Kopf extends BaseKopf {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['data', 'user_id'], 'required'],
            [['data'], 'string'],
            [['user_id'], 'integer']
        ]);
    }

}
