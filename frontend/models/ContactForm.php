<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class ContactForm extends Model {

    const fromName = 'Thomas Kipp';
    const fromEmail = 'kipp.thomas@tklustig.de';

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
        /*
          return Yii::$app->mailer->compose()
          ->setTo($email)
          ->setFrom([$this->email => $this->nachname])
          ->setSubject($this->betreff)
          ->setTextBody($this->inhalt)
          ->send(); */
        try {
            $month = array('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
            $tage = array('Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag');
            $heute = getdate();
            $heute = $tage[$heute['wday']] . ', den ' . $heute['mday'] . '.' . $month[$heute['mon'] - 1] . ' ' . $heute['year'];
            $to = Yii::$app->params['adminEmail'];
            $subject = 'yii2-Message von ' . $email . '::' . $this->betreff;
            $nachricht = "eine neue Message vom $heute:<br>$this->inhalt";
            $header = 'MIME-Version: 1.0' . "\r\n";
            $header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $header .= 'From:  ' . ContactForm::fromName . ' <' . ContactForm::fromEmail . '>' . " \r\n" .
                    'Reply-To: ' . ContactForm::fromEmail . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
            $umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
            $ersetzen = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
            $send_mail = str_replace($umlaute, $ersetzen, $nachricht);
            if (mail($to, $subject, $send_mail, $header))
                return true;
            else
                return false;
        } catch (\Exception $e) {
            return false;
        }
    }

}
