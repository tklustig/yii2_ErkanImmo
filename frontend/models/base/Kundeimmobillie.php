<?php

namespace frontend\models\base;

use Yii;

/**
 * This is the base model class for table "kundeimmobillie".
 *
 * @property integer $id
 * @property integer $kunde_id
 * @property integer $immobilien_id
 *
 * @property \frontend\models\Immobilien $immobilien
 * @property \frontend\models\Kunde $kunde
 */
class Kundeimmobillie extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'immobilien',
            'kunde'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kunde_id', 'immobilien_id'], 'required'],
            [['kunde_id', 'immobilien_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kundeimmobillie';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'kunde_id' => Yii::t('app', 'Kunde ID'),
            'immobilien_id' => Yii::t('app', 'Immobilien ID'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImmobilien()
    {
        return $this->hasOne(\frontend\models\Immobilien::className(), ['id' => 'immobilien_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKunde()
    {
        return $this->hasOne(\frontend\models\Kunde::className(), ['id' => 'kunde_id']);
    }
    }
