<?php

namespace frontend\models\base;

use Yii;

/**
 * This is the base model class for table "l_dateianhang_art".
 *
 * @property integer $id
 * @property string $bezeichnung
 *
 * @property \frontend\models\Dateianhang[] $dateianhangs
 */
class LDateianhangArt extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'dateianhangs'
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
        return 'l_dateianhang_art';
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
    public function getDateianhangs()
    {
        return $this->hasMany(\frontend\models\Dateianhang::className(), ['l_dateianhang_art_id' => 'id']);
    }
    }
