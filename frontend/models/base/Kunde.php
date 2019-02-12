<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base model class for table "kunde".
 *
 * @property integer $id
 * @property integer $l_plz_id
 * @property string $geschlecht
 * @property string $vorname
 * @property string $nachname
 * @property string $stadt
 * @property string $strasse
 * @property string $geburtsdatum
 * @property integer $solvenz
 * @property integer $bankverbindung_id
 * @property string $angelegt_am
 * @property string $aktualisiert_am
 * @property integer $angelegt_von
 * @property integer $aktualisiert_von
 *
 * @property \frontend\models\Adminbesichtigungkunde[] $adminbesichtigungkundes
 * @property \frontend\models\EDateianhang[] $eDateianhangs
 * @property \frontend\models\Bankverbindung $bankverbindung
 * @property \frontend\models\User $aktualisiertVon
 * @property \frontend\models\Kundeimmobillie[] $kundeimmobillies
 */
class Kunde extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'adminbesichtigungkundes',
            'eDateianhangs',
            'bankverbindung',
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
            [['l_plz_id', 'geschlecht', 'vorname', 'nachname', 'stadt', 'strasse'], 'required'],
            [['l_plz_id', 'bankverbindung_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['geburtsdatum', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['geschlecht'], 'string', 'max' => 64],
            [['vorname', 'nachname', 'stadt'], 'string', 'max' => 255],
            [['strasse'], 'string', 'max' => 44],
            [['solvenz'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kunde';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'l_plz_id' => Yii::t('app', 'L Plz ID'),
            'geschlecht' => Yii::t('app', 'Geschlecht'),
            'vorname' => Yii::t('app', 'Vorname'),
            'nachname' => Yii::t('app', 'Nachname'),
            'stadt' => Yii::t('app', 'Stadt'),
            'strasse' => Yii::t('app', 'Strasse'),
            'geburtsdatum' => Yii::t('app', 'Geburtsdatum'),
            'solvenz' => Yii::t('app', 'Solvenz'),
            'bankverbindung_id' => Yii::t('app', 'Bankverbindung ID'),
            'angelegt_am' => Yii::t('app', 'Angelegt Am'),
            'aktualisiert_am' => Yii::t('app', 'Aktualisiert Am'),
            'angelegt_von' => Yii::t('app', 'Angelegt Von'),
            'aktualisiert_von' => Yii::t('app', 'Aktualisiert Von'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminbesichtigungkundes()
    {
        return $this->hasMany(\frontend\models\Adminbesichtigungkunde::className(), ['kunde_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEDateianhangs()
    {
        return $this->hasMany(\frontend\models\EDateianhang::className(), ['kunde_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankverbindung()
    {
        return $this->hasOne(\frontend\models\Bankverbindung::className(), ['id' => 'bankverbindung_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAktualisiertVon()
    {
        return $this->hasOne(\frontend\models\User::className(), ['id' => 'aktualisiert_von']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKundeimmobillies()
    {
        return $this->hasMany(\frontend\models\Kundeimmobillie::className(), ['kunde_id' => 'id']);
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
        ];
    }
}
