<?php

namespace backend\models\base;

use Yii;

/**
 * This is the base model class for table "l_plz".
 *
 * @property integer $id
 * @property string $plz
 * @property string $ort
 * @property string $bl
 */
class LPlz extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            ''
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plz', 'ort', 'bl'], 'required'],
            [['plz'], 'string', 'max' => 5],
            [['ort', 'bl'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_plz';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'plz' => Yii::t('app', 'Plz'),
            'ort' => Yii::t('app', 'Ort'),
            'bl' => Yii::t('app', 'Bl'),
        ];
    }
}
