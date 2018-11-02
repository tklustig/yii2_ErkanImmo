<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "l_heizungsart".
 *
 * @property integer $id
 * @property string $bezeichnung
 *
 * @property \frontend\models\Immobilien[] $immobiliens
 */
class LHeizungsart extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'immobiliens'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bezeichnung'], 'required'],
            [['bezeichnung'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_heizungsart';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bezeichnung' => Yii::t('app', 'Bezeichnung'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImmobiliens()
    {
        return $this->hasMany(\frontend\models\Immobilien::className(), ['l_heizungsart_id' => 'id']);
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
