<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "immobilien".
 *
 * @property integer $id
 * @property string $bezeichnung
 * @property string $strasse
 * @property integer $wohnflaeche
 * @property integer $raeume
 * @property string $geldbetrag
 * @property integer $l_plz_id
 * @property integer $l_stadt_id
 * @property integer $user_id
 * @property integer $l_art_id
 * @property string $angelegt_am
 * @property string $aktualisiert_am
 * @property integer $angelegt_von
 * @property integer $aktualisiert_von
 *
 * @property \backend\models\Besichtigungstermin[] $besichtigungstermins
 * @property \backend\models\EDateianhang[] $eDateianhangs
 * @property \backend\models\LArt $lArt
 * @property \backend\models\User $user
 * @property \backend\models\LStadt $lStadt
 * @property \backend\models\User $angelegtVon
 * @property \backend\models\User $aktualisiertVon
 * @property \backend\models\Kundeimmobillie[] $kundeimmobillies
 */
class Immobilien extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'besichtigungstermins',
            'eDateianhangs',
            'lArt',
            'user',
            'lStadt',
            'angelegtVon',
            'aktualisiertVon',
            'kundeimmobillies'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['wohnflaeche', 'raeume', 'l_plz_id', 'l_stadt_id', 'user_id', 'l_art_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['bezeichnung'], 'string'],
            [['strasse', 'wohnflaeche', 'raeume', 'geldbetrag', 'l_plz_id', 'l_stadt_id', 'user_id', 'l_art_id'], 'required'],
            [['geldbetrag'], 'number'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['strasse'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'immobilien';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bezeichnung' => Yii::t('app', 'Beschreibung'),
            'strasse' => Yii::t('app', 'Strasse'),
            'wohnflaeche' => Yii::t('app', 'Wohnfläche(m^2)'),
            'raeume' => Yii::t('app', 'Räume'),
            'geldbetrag' => Yii::t('app', 'Kosten(€)'),
            'l_plz_id' => Yii::t('app', 'Plz'),
            'l_stadt_id' => Yii::t('app', 'Stadt'),
            'user_id' => Yii::t('app', 'User'),
            'l_art_id' => Yii::t('app', 'Angebotsart'),
            'angelegt_am' => Yii::t('app', 'angelegt am'),
            'aktualisiert_am' => Yii::t('app', 'aktualisiert am'),
            'angelegt_von' => Yii::t('app', 'angelegt von'),
            'aktualisiert_von' => Yii::t('app', 'aktualisiert von'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBesichtigungstermins() {
        return $this->hasMany(\frontend\models\Besichtigungstermin::className(), ['Immobilien_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEDateianhangs() {
        return $this->hasMany(\frontend\models\EDateianhang::className(), ['immobilien_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLArt() {
        return $this->hasOne(\frontend\models\LArt::className(), ['id' => 'l_art_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLStadt() {
        return $this->hasOne(\frontend\models\LStadt::className(), ['id' => 'l_stadt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAngelegtVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'angelegt_von']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAktualisiertVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'aktualisiert_von']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKundeimmobillies() {
        return $this->hasMany(\frontend\models\Kundeimmobillie::className(), ['immobilien_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return array mixed
     */
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
