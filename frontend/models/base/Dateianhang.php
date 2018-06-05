<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "dateianhang".
 *
 * @property integer $id
 * @property string $bezeichnung
 * @property string $dateiname
 * @property string $angelegt_am
 * @property string $aktualisert_am
 * @property integer $angelegt_von
 * @property integer $aktualisiert_von
 * @property integer $l_dateianhang_art_id
 * @property integer $e_dateianhang_id
 *
 * @property \frontend\models\EDateianhang $eDateianhang
 * @property \frontend\models\LDateianhangArt $lDateianhangArt
 */
class Dateianhang extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'eDateianhang',
            'lDateianhangArt'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dateiname', 'angelegt_von', 'aktualisiert_von', 'l_dateianhang_art_id', 'e_dateianhang_id'], 'required'],
            [['angelegt_am', 'aktualisert_am'], 'safe'],
            [['angelegt_von', 'aktualisiert_von', 'l_dateianhang_art_id', 'e_dateianhang_id'], 'integer'],
            [['bezeichnung', 'dateiname'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dateianhang';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bezeichnung' => Yii::t('app', 'Bezeichnung'),
            'dateiname' => Yii::t('app', 'Dateiname'),
            'angelegt_am' => Yii::t('app', 'Angelegt Am'),
            'aktualisert_am' => Yii::t('app', 'Aktualisert Am'),
            'angelegt_von' => Yii::t('app', 'Angelegt Von'),
            'aktualisiert_von' => Yii::t('app', 'Aktualisiert Von'),
            'l_dateianhang_art_id' => Yii::t('app', 'L Dateianhang Art ID'),
            'e_dateianhang_id' => Yii::t('app', 'E Dateianhang ID'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEDateianhang()
    {
        return $this->hasOne(\frontend\models\EDateianhang::className(), ['id' => 'e_dateianhang_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLDateianhangArt()
    {
        return $this->hasOne(\frontend\models\LDateianhangArt::className(), ['id' => 'l_dateianhang_art_id']);
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
