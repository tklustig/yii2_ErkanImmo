<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class Bankverbindung extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'aktualisiertVon',
            'kundes'
        ];
    }

    public function rules() {
        return [
            [['laenderkennung', 'blz', 'kontoNr'], 'required', 'except' => 'create_Bankverbindung'],
            [['laenderkennung', 'blz', 'kontoNr'], 'safe', 'on' => 'create_Bankverbindung'],
            [['blz', 'kontoNr', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['laenderkennung'], 'string', 'max' => 3],
            [['institut'], 'string', 'max' => 255],
            [['iban'], 'string', 'max' => 32],
            [['bic'], 'string', 'max' => 16]
        ];
    }

    public static function tableName() {
        return 'bankverbindung';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'laenderkennung' => Yii::t('app', 'Laenderkennung'),
            'institut' => Yii::t('app', 'Institut'),
            'blz' => Yii::t('app', 'Blz'),
            'kontoNr' => Yii::t('app', 'Konto Nr'),
            'iban' => Yii::t('app', 'Iban'),
            'bic' => Yii::t('app', 'Bic'),
            'angelegt_am' => Yii::t('app', 'Angelegt Am'),
            'aktualisiert_am' => Yii::t('app', 'Aktualisiert Am'),
            'angelegt_von' => Yii::t('app', 'Angelegt Von'),
            'aktualisiert_von' => Yii::t('app', 'Aktualisiert Von'),
        ];
    }

    public function getAktualisiertVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'aktualisiert_von']);
    }
        public function getAngelegtVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'aktualisiert_von']);
    }

    public function getKundes() {
        return $this->hasMany(\frontend\models\Kunde::className(), ['bankverbindung_id' => 'id']);
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
