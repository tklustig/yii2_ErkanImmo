<?php

namespace backend\models\base;

use Yii;

class LRechnungsart extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    public function relationNames()
    {
        return [
            'rechnungs'
        ];
    }

    public function rules()
    {
        return [
            [['data', 'art'], 'required'],
            [['data'], 'string'],
            [['art'], 'string', 'max' => 32],
        ];
    }

    public static function tableName()
    {
        return 'l_rechnungsart';
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'data' => Yii::t('app', 'Data'),
            'art' => Yii::t('app', 'Art'),
        ];
    }

    public function getRechnungs()
    {
        return $this->hasMany(\backend\models\Rechnung::className(), ['rechungsart_id' => 'id']);
    }
    }
