<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

class Kunde extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'adminbesichtigungkundes',
            'eDateianhangs',
            'bankverbindung',
            'aktualisiertVon',
            'kundeimmobillies',
            'lPlz',
            'geschlecht0',
        ];
    }

    public function rules() {
        return [
            [['l_plz_id', 'geschlecht', 'vorname', 'nachname', 'stadt', 'strasse'], 'required'],
            [['l_plz_id', 'angelegt_von', 'aktualisiert_von', 'geschlecht'], 'integer'],
            [['geburtsdatum', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            ['email', 'email'],
            [['vorname', 'nachname', 'stadt'], 'string', 'max' => 255],
            [['strasse'], 'string', 'max' => 44],
            [['solvenz'], 'boolean'],
            ['telefon', 'filter', 'filter' => function ($value) {
                    return $value;
                }],
        ];
    }

    public static function tableName() {
        return 'kunde';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'l_plz_id' => Yii::t('app', 'PLZ'),
            'geschlecht' => Yii::t('app', 'Geschlecht'),
            'vorname' => Yii::t('app', 'Vorname'),
            'nachname' => Yii::t('app', 'Nachname'),
            'stadt' => Yii::t('app', 'Stadt'),
            'strasse' => Yii::t('app', 'Strasse'),
            'geburtsdatum' => Yii::t('app', 'Geburtsdatum'),
            'solvenz' => Yii::t('app', 'Solvenz'),
            'telefon' => Yii::t('app', 'Telefon'),
            'email' => Yii::t('app', 'Email'),
            'bankverbindung_id' => Yii::t('app', 'Bankverbindung ID'),
            'angelegt_am' => Yii::t('app', 'Angelegt Am'),
            'aktualisiert_am' => Yii::t('app', 'Aktualisiert Am'),
            'angelegt_von' => Yii::t('app', 'Angelegt Von'),
            'aktualisiert_von' => Yii::t('app', 'Aktualisiert Von'),
        ];
    }

    public function getAdminbesichtigungkundes() {
        return $this->hasMany(\frontend\models\Adminbesichtigungkunde::className(), ['kunde_id' => 'id']);
    }

    public function getEDateianhangs() {
        return $this->hasMany(\frontend\models\EDateianhang::className(), ['kunde_id' => 'id']);
    }

    public function getBankverbindung() {
        return $this->hasOne(\backend\models\Bankverbindung::className(), ['id' => 'bankverbindung_id']);
    }

    public function getAktualisiertVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'aktualisiert_von']);
    }

    public function getKundeimmobillies() {
        return $this->hasMany(\frontend\models\Kundeimmobillie::className(), ['kunde_id' => 'id']);
    }

    public function getLPlz() {
        return $this->hasOne(\frontend\models\LPlz::className(), ['id' => 'l_plz_id']);
    }

    public function getGeschlecht0() {
        return $this->hasOne(\backend\models\LGeschlecht::className(), ['id' => 'geschlecht']);
    }

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'angelegt_am',
                'updatedAtAttribute' => 'aktualisiert_am',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

}
