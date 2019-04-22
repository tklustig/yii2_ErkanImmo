<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class Firma extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'angelegtVon',
            'aktualisiertVon',
            'lPlz',
            'lRechtsform'
        ];
    }

    public function rules() {
        return [
            [['firmenname', 'l_rechtsform_id', 'strasse', 'l_plz_id', 'ort','bankdaten'], 'required'],
            [['l_rechtsform_id', 'hausnummer', 'l_plz_id', 'anzahlMitarbeiter', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['firmenname', 'strasse', 'ort', 'umsatzsteuerID'], 'string', 'max' => 64],
            [['geschaeftsfuehrer', 'prokurist'], 'string', 'max' => 32],
            [['bankdaten'], 'string', 'max' => 256],
            [['l_plz_id'], 'unique'],
            [['aktualisiert_von'], 'unique'],
            [['angelegt_von'], 'unique']
        ];
    }

    public static function tableName() {
        return 'firma';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'firmenname' => Yii::t('app', 'Firmenname'),
            'l_rechtsform_id' => Yii::t('app', 'L Rechtsform ID'),
            'strasse' => Yii::t('app', 'Strasse'),
            'hausnummer' => Yii::t('app', 'Hausnummer'),
            'l_plz_id' => Yii::t('app', 'L Plz ID'),
            'ort' => Yii::t('app', 'Ort'),
            'geschaeftsfuehrer' => Yii::t('app', 'Geschaeftsfuehrer'),
            'prokurist' => Yii::t('app', 'Prokurist'),
            'umsatzsteuerID' => Yii::t('app', 'Umsatzsteuer ID'),
            'bankdaten' => Yii::t('app', 'Bankdaten'),
            'anzahlMitarbeiter' => Yii::t('app', 'Anzahl Mitarbeiter'),
            'angelegt_von' => Yii::t('app', 'Angelegt Von'),
            'aktualisiert_von' => Yii::t('app', 'Aktualisiert Von'),
            'angelegt_am' => Yii::t('app', 'Angelegt Am'),
            'aktualisiert_am' => Yii::t('app', 'Aktualisiert Am'),
        ];
    }

    public function getAngelegtVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'angelegt_von']);
    }

    public function getAktualisiertVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'aktualisiert_von']);
    }

    public function getLPlz() {
        return $this->hasOne(\frontend\models\LPlz::className(), ['id' => 'l_plz_id']);
    }

    public function getLRechtsform() {
        return $this->hasOne(\backend\models\LRechtsform::className(), ['id' => 'l_rechtsform_id']);
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
