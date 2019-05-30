<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use frontend\models\EDateianhang;
use frontend\models\Dateianhang;

class Mail extends \yii\db\ActiveRecord {

    public $checkBoxDelete;

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'mailserver',
            'angelegtVon',
            'aktualisiertVon',
            'textbaustein'
        ];
    }

    public function rules() {
        return [
            [['id_mailserver', 'mail_from', 'mail_to', 'betreff', 'bodytext'], 'required'],
            [['id_mailserver', 'textbaustein_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['bodytext', 'vorlage'], 'string'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['mail_from', 'betreff'], 'string', 'max' => 64],
            [['mail_to', 'mail_cc', 'mail_bcc'], 'string', 'max' => 256],
            [['checkBoxDelete'], 'boolean'],
        ];
    }

    public static function tableName() {
        return 'mail';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_mailserver' => Yii::t('app', 'Id Mailserver'),
            'mail_from' => Yii::t('app', 'Mail From'),
            'mail_to' => Yii::t('app', 'Mail To'),
            'mail_cc' => Yii::t('app', 'Mail Cc'),
            'mail_bcc' => Yii::t('app', 'Mail Bcc'),
            'betreff' => Yii::t('app', 'Betreff'),
            'bodytext' => Yii::t('app', 'Bodytext'),
            'textbaustein_id' => Yii::t('app', 'Textbaustein ID'),
            'vorlage' => Yii::t('app', 'Vorlage'),
            'angelegt_am' => Yii::t('app', 'Angelegt Am'),
            'angelegt_von' => Yii::t('app', 'Angelegt Von'),
            'aktualisiert_am' => Yii::t('app', 'Aktualisiert Am'),
            'aktualisiert_von' => Yii::t('app', 'Aktualisiert Von'),
        ];
    }

    public function getMailserver() {
        return $this->hasOne(\backend\models\Mailserver::className(), ['id' => 'id_mailserver']);
    }

    public function getAngelegtVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'angelegt_von']);
    }

    public function getAktualisiertVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'aktualisiert_von']);
    }

    public function getTextbaustein() {
        return $this->hasOne(\backend\models\LTextbaustein::className(), ['id' => 'textbaustein_id']);
    }

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'angelegt_am',
                'updatedAtAttribute' => 'aktualisiert_am',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'angelegt_von',
                'updatedByAttribute' => 'aktualisiert_von',
            ],
        ];
    }

    public static function getBild($pk) {
        if (!empty(EDateianhang::findOne(['kunde_id' => $pk]))) {
            $idOfEdateiA = EDateianhang::findOne(['kunde_id' => $pk])->id;
            if (Dateianhang::findOne(['e_dateianhang_id' => $idOfEdateiA])->bezeichnung == 'Kundenbild') {
                $pictureN = Dateianhang::findOne(['e_dateianhang_id' => $idOfEdateiA])->dateiname;
                $path = '@web/img/';
                $picture = $path . $pictureN;
            } else
                $picture = null;
        } else
            $picture = null;
        return $picture;
    }

}
