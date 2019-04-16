<?php

namespace backend\models\base;

use Yii;

class LBegriffe extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    public function rules()
    {
        return [
            [['typ', 'data'], 'required'],
            [['typ'], 'string', 'max' => 256],
            [['data'], 'string', 'max' => 128]
        ];
    }

    public static function tableName()
    {
        return 'l_begriffe';
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'typ' => Yii::t('app', 'Typ'),
            'data' => Yii::t('app', 'Data'),
        ];
    }
}
