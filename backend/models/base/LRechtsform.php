<?php

namespace backend\models\base;

use Yii;

/**
 * This is the base model class for table "l_rechtsform".
 *
 * @property integer $id
 * @property string $typus
 *
 * @property \backend\models\Firma[] $firmas
 */
class LRechtsform extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'firmas'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['typus'], 'required'],
            [['typus'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_rechtsform';
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
    public function getFirmas()
    {
        return $this->hasMany(\backend\models\Firma::className(), ['l_rechtsform_id' => 'id']);
    }
    }
