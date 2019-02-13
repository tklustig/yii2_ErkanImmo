<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base model class for table "bankverbindung".
 *
 * @property integer $id
 * @property string $laenderkennung
 * @property string $institut
 * @property integer $blz
 * @property integer $kontoNr
 * @property string $iban
 * @property string $bic
 * @property string $angelegt_am
 * @property string $aktualisiert_am
 * @property integer $angelegt_von
 * @property integer $aktualisiert_von
 *
 * @property \frontend\models\User $aktualisiertVon
 * @property \frontend\models\Kunde[] $kundes
 */
class Bankverbindung extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'aktualisiertVon',
            'kundes'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['laenderkennung', 'blz', 'kontoNr'], 'required'],
            [['blz', 'kontoNr', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['laenderkennung'], 'string', 'max' => 3],
            [['institut'], 'string', 'max' => 255],
            [['iban'], 'string', 'max' => 32],
            [['bic'], 'string', 'max' => 8]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bankverbindung';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'laenderkennung' => Yii::t('app', 'Laenderkennung'),
            'institut' => Yii::t('app', 'Institut'),
            'blz' => Yii::t('app', 'Blz'),
            'kontoNr' => Yii::t('app', 'Konto Nr'),
            'iban' => Yii::t('app', 'Iban'),
            'bic' => Yii::t('app', 'Bic'),
            'angelegt_am' => Yii::t('app', 'Angelegt Am'),
            'aktualisiert_am' => Yii::t('app', 'Aktualisiert Am'),
            'angelegt_von' => Yii::t('app', 'Angelegt Von'),
            'aktualisiert_von' => Yii::t('app', 'Aktualisiert Von'),
        ];
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
    public function getKundes()
    {
        return $this->hasMany(\frontend\models\Kunde::className(), ['bankverbindung_id' => 'id']);
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
