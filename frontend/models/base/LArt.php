<?php

namespace frontend\models\base;

use Yii;

/**
 * This is the base model class for table "l_art".
 *
 * @property integer $id
 * @property string $bezeichnung
 *
 * @property \frontend\models\Immobilien[] $immobiliens
 */
class LArt extends \yii\db\ActiveRecord
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
            [['bezeichnung'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_art';
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
        return $this->hasMany(\frontend\models\Immobilien::className(), ['l_art_id' => 'id']);
    }
    }
