<?php

namespace frontend\models\base;

use Yii;

/**
 * This is the base model class for table "l_stadt".
 *
 * @property integer $id
 * @property string $stadt
 *
 * @property \frontend\models\Besichtigungstermin[] $besichtigungstermins
 * @property \frontend\models\Immobilien[] $immobiliens
 * @property \frontend\models\Kunde[] $kundes
 */
class LStadt extends \yii\db\ActiveRecord
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
            'immobiliens',
            'kundes'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stadt'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_stadt';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'stadt' => Yii::t('app', 'Stadt'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBesichtigungstermins()
    {
        return $this->hasMany(\frontend\models\Besichtigungstermin::className(), ['l_stadt_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImmobiliens()
    {
        return $this->hasMany(\frontend\models\Immobilien::className(), ['l_stadt_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKundes()
    {
        return $this->hasMany(\frontend\models\Kunde::className(), ['l_stadt_id' => 'id']);
    }
    }
