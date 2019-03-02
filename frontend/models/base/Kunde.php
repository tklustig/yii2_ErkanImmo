<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class Kunde extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'adminbesichtigungkundes',
            'eDateianhangs',
            'bankverbindung',
            'aktualisiertVon',
            'kundeimmobillies'
        ];
    }

    public function rules() {
        return [
            [['bankverbindung_id'], 'integer', 'except' => 'update_kunde'],
            [['bankverbindung_id',], 'string', 'on' => 'update_kunde'],
            [['l_plz_id', 'geschlecht', 'vorname', 'nachname', 'stadt', 'strasse'], 'required'],
            [['l_plz_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['geburtsdatum', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            ['email', 'email'],
            [['geschlecht'], 'string', 'max' => 64],
            [['vorname', 'nachname', 'stadt'], 'string', 'max' => 255],
            [['strasse'], 'string', 'max' => 44],
            [['solvenz'], 'string', 'max' => 1],
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
            'id' => 'ID',
            'l_plz_id' => 'L Plz ID',
            'geschlecht' => 'Geschlecht',
            'vorname' => 'Vorname',
            'nachname' => 'Nachname',
            'stadt' => 'Stadt',
            'strasse' => 'Strasse',
            'geburtsdatum' => 'Geburtsdatum',
            'solvenz' => 'Solvenz',
            'telefon' => 'Telefon',
            'email' => 'Email',
            'bankverbindung_id' => 'Bankverbindung ID',
            'angelegt_am' => 'Angelegt Am',
            'aktualisiert_am' => 'Aktualisiert Am',
            'angelegt_von' => 'Angelegt Von',
            'aktualisiert_von' => 'Aktualisiert Von',
        ];
    }

    public function getAdminbesichtigungkundes() {
        return $this->hasMany(\frontend\models\Adminbesichtigungkunde::className(), ['kunde_id' => 'id']);
    }

    public function getEDateianhangs() {
        return $this->hasMany(\frontend\models\EDateianhang::className(), ['kunde_id' => 'id']);
    }

    public function getBankverbindung() {
        return $this->hasOne(\frontend\models\Bankverbindung::className(), ['id' => 'bankverbindung_id']);
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
