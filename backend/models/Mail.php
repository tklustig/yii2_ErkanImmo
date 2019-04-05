<?php

namespace backend\models;

use Yii;
use \backend\models\base\Mail as BaseMail;

/**
 * This is the model class for table "mail".
 */
class Mail extends BaseMail {

    /**
     * @inheritdoc
     */
    public function rules() {
        return array_replace_recursive(parent::rules(),
                [
                    [['id', 'id_mailserver', 'mail_from', 'mail_to', 'betreff', 'bodytext'], 'required'],
                    [['id', 'id_mailserver', 'angelegt_von', 'aktualisiert_von'], 'integer'],
                    [['bodytext'], 'string'],
                    [['angelegt_am', 'aktualisiert_am'], 'safe'],
                    [['mail_from', 'mail_to', 'mail_cc', 'mail_bcc'], 'email'],
                    [['betreff'], 'string', 'max' => 64],
                    [['angelegt_von'], 'unique'],
                    [['aktualisiert_von'], 'unique']
        ]);
    }

}
