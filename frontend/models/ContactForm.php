<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class ContactForm extends Model {

    public $vorname;
    public $nachname;
    public $email;
    public $betreff;
    public $inhalt;
    public $verifyCode;

    public function rules() {
        return [
            [['vorname', 'nachname', 'betreff', 'inhalt'], 'string'],
            ['email', 'email'],
            [['vorname', 'nachname', 'email', 'inhalt'], 'required'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels() {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    public function sendEmail($email) {
        return Yii::$app->mailer->compose()
                        ->setTo($email)
                        ->setFrom([$this->email => $this->nachname])
                        ->setSubject($this->betreff)
                        ->setTextBody($this->inhalt)
                        ->send();
    }

}
