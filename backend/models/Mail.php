<?php

namespace backend\models;

use Yii;
use \backend\models\base\Mail as BaseMail;

/**
 * This is the model class for table "mail".
 */
class Mail extends BaseMail {

    public $checkBoxDelete;

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['id_mailserver', 'mail_from', 'mail_to', 'betreff', 'bodytext'], 'required'],
            [['id_mailserver', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['bodytext'], 'string'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['mail_from', 'mail_to', 'mail_cc', 'mail_bcc', 'betreff'], 'string', 'max' => 256],
            [['checkBoxDelete'], 'boolean'],
        ]);
    }

}
