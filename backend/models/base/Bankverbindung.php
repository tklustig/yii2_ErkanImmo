<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class Bankverbindung extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;
    public $blz;

    public function relationNames() {
        return [
            'aktualisiertVon',
            'kundes'
        ];
    }

    public function rules() {
        return [
            [['art', 'iban'], 'required'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['angelegt_von', 'aktualisiert_von'], 'integer'],
            [['art', 'iban', 'bic'], 'string', 'max' => 32]
        ];
    }

    public static function tableName() {
        return 'bankverbindung';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'art' => Yii::t('app', 'Institut'),
            'iban' => Yii::t('app', 'IBAN'),
            'bic' => Yii::t('app', 'BIC'),
            'angelegt_am' => Yii::t('app', 'angelegt am'),
            'aktualisiert_am' => Yii::t('app', 'aktualisiert am'),
            'angelegt_von' => Yii::t('app', 'angelegt von'),
            'aktualisiert_von' => Yii::t('app', 'aktualisiert von'),
        ];
    }

    public function getAktualisiertVon() {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'aktualisiert_von']);
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
                'updatedByAttribute' => 'aktualisert_von',
            ],
        ];
    }

}
