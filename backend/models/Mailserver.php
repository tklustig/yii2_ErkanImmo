<?php

namespace backend\models;

use Yii;
use \backend\models\base\Mailserver as BaseMailserver;

class Mailserver extends BaseMailserver {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['username', 'password', 'port', 'useEncryption', 'serverHost'], 'required'],
            [['port', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            ['serverURL', 'ip', 'ipv6' => false],
            [['serverHost'], 'string', 'max' => 64],
            [['username', 'password'], 'string', 'max' => 32],
            [['useEncryption'], 'string', 'max' => 1],
            [['encryption'], 'string', 'max' => 6]
        ]);
    }

}
