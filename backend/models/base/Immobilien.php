<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class Immobilien extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'besichtigungstermins',
            'eDateianhangs',
            'lArt',
            'user',
            'angelegtVon',
            'aktualisiertVon',
            'lHeizungsart',
            'kundeimmobillies'
        ];
    }

    public function rules() {
        return [
            [['wohnflaeche', 'raeume', 'k_grundstuecksgroesse', 'l_plz_id', 'user_id', 'l_art_id', 'l_heizungsart_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['bezeichnung', 'sonstiges'], 'string'],
            [['strasse', 'wohnflaeche', 'raeume', 'geldbetrag', 'l_plz_id', 'stadt', 'user_id', 'l_art_id'], 'required'],
            [['geldbetrag', 'k_provision', 'v_nebenkosten'], 'number'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['strasse'], 'string', 'max' => 45],
            [['balkon_vorhanden', 'fahrstuhl_vorhanden'], 'string', 'max' => 1],
            [['stadt'], 'string', 'max' => 255]
        ];
    }

    public static function tableName() {
        return 'immobilien';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bezeichnung' => Yii::t('app', 'Bezeichnung'),
            'sonstiges' => Yii::t('app', 'Sonstiges'),
            'strasse' => Yii::t('app', 'Strasse'),
            'wohnflaeche' => Yii::t('app', 'Wohnfläche(m^2)'),
            'raeume' => Yii::t('app', 'Räume'),
            'geldbetrag' => Yii::t('app', 'Geldbetrag'),
            'k_grundstuecksgroesse' => Yii::t('app', 'Grundgrösse(m^2)'),
            'k_provision' => Yii::t('app', 'Provision'),
            'v_nebenkosten' => Yii::t('app', 'Nebenkosten'),
            'balkon_vorhanden' => Yii::t('app', 'Balkon vorhanden'),
            'fahrstuhl_vorhanden' => Yii::t('app', 'Fahrstuhl vorhanden'),
            'l_plz_id' => Yii::t('app', 'Plz'),
            'stadt' => Yii::t('app', 'Stadt'),
            'user_id' => Yii::t('app', 'User'),
            'l_art_id' => Yii::t('app', 'Art'),
            'l_heizungsart_id' => Yii::t('app', 'Befeuerung'),
            'angelegt_am' => Yii::t('app', 'angelegt am'),
            'aktualisiert_am' => Yii::t('app', 'aktualisiert am'),
            'angelegt_von' => Yii::t('app', 'angelegt von'),
            'aktualisiert_von' => Yii::t('app', 'aktualisiert von'),
        ];
    }

    public function getBesichtigungstermins() {
        return $this->hasMany(\frontend\models\Besichtigungstermin::className(), ['Immobilien_id' => 'id']);
    }

    public function getEDateianhangs() {
        return $this->hasMany(\frontend\models\EDateianhang::className(), ['immobilien_id' => 'id']);
    }

    public function getLArt() {
        return $this->hasOne(\frontend\models\LArt::className(), ['id' => 'l_art_id']);
    }

    public function getUser() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    public function getAngelegtVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'angelegt_von']);
    }

    public function getAktualisiertVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'aktualisiert_von']);
    }

    public function getLHeizungsart() {
        return $this->hasOne(\frontend\models\LHeizungsart::className(), ['id' => 'l_heizungsart_id']);
    }

    public function getLPlz() {
        return $this->hasOne(\frontend\models\LPlz::className(), ['id' => 'l_plz_id']);
    }

    public function getKundeimmobillies() {
        return $this->hasMany(\frontend\models\Kundeimmobillie::className(), ['immobilien_id' => 'id']);
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
