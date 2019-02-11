<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "bankverbindung".
 *
 * @property integer $id
 * @property string $art
 * @property string $iban
 * @property string $bic
 * @property string $angelegt_am
 * @property string $aktualisiert_am
 * @property integer $angelegt_von
 * @property integer $aktualisiert_von
 *
 * @property \backend\models\User $aktualisiertVon
 * @property \backend\models\Kunde[] $kundes
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
            [['art', 'iban'], 'required'],
            [['angelegt_am', 'aktualisiert_am'], 'safe'],
            [['angelegt_von', 'aktualisiert_von'], 'integer'],
            [['art', 'iban', 'bic'], 'string', 'max' => 32]
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
            'art' => Yii::t('app', 'Art'),
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
        return $this->hasOne(\backend\models\User::className(), ['id' => 'aktualisiert_von']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKundes()
    {
        return $this->hasMany(\backend\models\Kunde::className(), ['bankverbindung_id' => 'id']);
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
                'updatedByAttribute' => 'aktualisert_von',
            ],
        ];
    }
}
