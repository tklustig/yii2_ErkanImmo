<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class Kunde extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;
    
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
    public function rules()
    {
        return [
            [['l_plz_id', 'vorname', 'nachname', 'stadt', 'strasse', 'geburtsdatum', 'bankverbindung_id'], 'required'],
            [['l_plz_id', 'bankverbindung_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['geburtsdatum', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['vorname', 'nachname', 'stadt'], 'string', 'max' => 255],
            [['strasse'], 'string', 'max' => 255],
            [['solvenz'], 'integer', 'max' => 1],
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
     *
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock
     *
     */
    public function optimisticLock() {
        return 'lock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'l_plz_id' => Yii::t('app', 'L Plz ID'),
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
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
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
