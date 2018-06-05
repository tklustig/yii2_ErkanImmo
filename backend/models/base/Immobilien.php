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
class Immobilien extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
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
    public function rules()
    {
        return [
            [['bezeichnung'], 'string'],
            [['strasse', 'wohnflaeche', 'raeume', 'geldbetrag', 'l_plz_id', 'l_stadt_id', 'user_id', 'l_art_id'], 'required'],
            [['wohnflaeche', 'raeume', 'l_plz_id', 'l_stadt_id', 'user_id', 'l_art_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['geldbetrag'], 'number'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['strasse'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'immobilien';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bezeichnung' => Yii::t('app', 'Bezeichnung'),
            'strasse' => Yii::t('app', 'Strasse'),
            'wohnflaeche' => Yii::t('app', 'Wohnflaeche'),
            'raeume' => Yii::t('app', 'Raeume'),
            'geldbetrag' => Yii::t('app', 'Geldbetrag'),
            'l_plz_id' => Yii::t('app', 'L Plz ID'),
            'l_stadt_id' => Yii::t('app', 'L Stadt ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'l_art_id' => Yii::t('app', 'L Art ID'),
            'angelegt_am' => Yii::t('app', 'Angelegt Am'),
            'aktualisiert_am' => Yii::t('app', 'Aktualisiert Am'),
            'angelegt_von' => Yii::t('app', 'Angelegt Von'),
            'aktualisiert_von' => Yii::t('app', 'Aktualisiert Von'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBesichtigungstermins()
    {
        return $this->hasMany(\backend\models\Besichtigungstermin::className(), ['Immobilien_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEDateianhangs()
    {
        return $this->hasMany(\backend\models\EDateianhang::className(), ['immobilien_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLArt()
    {
        return $this->hasOne(\backend\models\LArt::className(), ['id' => 'l_art_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'user_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLStadt()
    {
        return $this->hasOne(\backend\models\LStadt::className(), ['id' => 'l_stadt_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAngelegtVon()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'angelegt_von']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAktualisiertVon()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'aktualisiert_von']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKundeimmobillies()
    {
        return $this->hasMany(\backend\models\Kundeimmobillie::className(), ['immobilien_id' => 'id']);
    }
    
    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
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
