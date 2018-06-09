<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "immobilien".
 *
 * @property integer $id
 * @property integer $id_bild
 * @property string $bezeichnung
 * @property string $sonstiges
 * @property string $strasse
 * @property integer $wohnflaeche
 * @property integer $raeume
 * @property string $geldbetrag
 * @property integer $k_grundstuecksgroesse
 * @property string $k_provision
 * @property string $v_nebenkosten
 * @property integer $balkon_vorhanden
 * @property integer $fahrstuhl_vorhanden
 * @property integer $l_plz_id
 * @property string $stadt
 * @property integer $user_id
 * @property integer $l_art_id
 * @property integer $l_heizungsart_id
 * @property string $angelegt_am
 * @property string $aktualisiert_am
 * @property integer $angelegt_von
 * @property integer $aktualisiert_von
 *
 * @property \backend\models\Besichtigungstermin[] $besichtigungstermins
 * @property \backend\models\EDateianhang[] $eDateianhangs
 * @property \backend\models\LArt $lArt
 * @property \backend\models\User $user
 * @property \backend\models\User $angelegtVon
 * @property \backend\models\User $aktualisiertVon
 * @property \backend\models\LHeizungsart $lHeizungsart
 * @property \backend\models\Kundeimmobillie[] $kundeimmobillies
 */
class Immobilien extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
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

    /**
     * @inheritdoc
     */
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
            'bezeichnung' => Yii::t('app', 'Bezeichnung'),
            'sonstiges' => Yii::t('app', 'Sonstiges'),
            'strasse' => Yii::t('app', 'Strasse'),
            'wohnflaeche' => Yii::t('app', 'Wohnflaeche'),
            'raeume' => Yii::t('app', 'Raeume'),
            'geldbetrag' => Yii::t('app', 'Geldbetrag'),
            'k_grundstuecksgroesse' => Yii::t('app', 'K Grundstuecksgroesse'),
            'k_provision' => Yii::t('app', 'K Provision'),
            'v_nebenkosten' => Yii::t('app', 'V Nebenkosten'),
            'balkon_vorhanden' => Yii::t('app', 'Balkon Vorhanden'),
            'fahrstuhl_vorhanden' => Yii::t('app', 'Fahrstuhl Vorhanden'),
            'l_plz_id' => Yii::t('app', 'L Plz ID'),
            'stadt' => Yii::t('app', 'Stadt'),
            'user_id' => Yii::t('app', 'User ID'),
            'l_art_id' => Yii::t('app', 'L Art ID'),
            'l_heizungsart_id' => Yii::t('app', 'L Heizungsart ID'),
            'angelegt_am' => Yii::t('app', 'Angelegt Am'),
            'aktualisiert_am' => Yii::t('app', 'Aktualisiert Am'),
            'angelegt_von' => Yii::t('app', 'Angelegt Von'),
            'aktualisiert_von' => Yii::t('app', 'Aktualisiert Von'),
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
    public function getLHeizungsart() {
        return $this->hasOne(\frontend\models\LHeizungsart::className(), ['id' => 'l_heizungsart_id']);
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
