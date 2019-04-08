<?php

namespace backend\models;

use Yii;
use \backend\models\base\Mail as BaseMail;

class Mail extends BaseMail {

    public $checkBoxDelete;

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['id_mailserver', 'mail_from', 'mail_to', 'betreff', 'bodytext'], 'required'],
            [['id_mailserver', 'textbaustein_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['bodytext', 'vorlage'], 'string'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['mail_from', 'betreff'], 'string', 'max' => 64],
            [['mail_to', 'mail_cc', 'mail_bcc'], 'string', 'max' => 256],
            [['checkBoxDelete'], 'boolean'],
        ]);
    }

}
