<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class Mailserver extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'angelegtVon',
            'aktualisiertVon'
        ];
    }

    public function rules() {
        return [
            [['username', 'password', 'port', 'useEncryption', 'serverHost'], 'required'],
            [['port', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            ['serverURL', 'ip', 'ipv6' => false],
            [['serverHost'], 'string', 'max' => 64],
            [['username', 'password'], 'string', 'max' => 32],
            [['useEncryption'], 'string', 'max' => 1],
            [['encryption'], 'string', 'max' => 6]
        ];
    }

    public static function tableName() {
        return 'mailserver';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'serverURL' => Yii::t('app', 'Server Url'),
            'serverHost' => Yii::t('app', 'Server Host'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'port' => Yii::t('app', 'Port'),
            'useEncryption' => Yii::t('app', 'Use Encryption'),
            'encryption' => Yii::t('app', 'Encryption'),
            'angelegt_von' => Yii::t('app', 'Angelegt Von'),
            'aktualisiert_von' => Yii::t('app', 'Aktualisiert Von'),
            'angelegt_am' => Yii::t('app', 'Angelegt Am'),
            'aktualisiert_am' => Yii::t('app', 'Aktualisiert Am'),
        ];
    }

    public function getAngelegtVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'angelegt_von']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAktualisiertVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'aktualisiert_von']);
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

}
