<?php

namespace backend\models\base;

use Yii;

/**
 * This is the base model class for table "l_geschlecht".
 *
 * @property integer $id
 * @property string $typus
 *
 * @property \backend\models\Kunde[] $kundes
 */
class LGeschlecht extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'kundes'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['typus'], 'required'],
            [['typus'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_geschlecht';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'typus' => Yii::t('app', 'Typus'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKundes()
    {
        return $this->hasMany(\backend\models\Kunde::className(), ['geschlecht' => 'id']);
    }
    }
