<?php

namespace backend\models\base;

use Yii;

class LLaenderkennung extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            ''
        ];
    }

    public function rules() {
        return [
            [['code', 'es', 'fr', 'it', 'ru'], 'required'],
            [['code'], 'string', 'max' => 2],
            [['en', 'de', 'es', 'fr', 'it', 'ru'], 'string', 'max' => 100]
        ];
    }

    public static function tableName() {
        return 'l_laenderkennung';
    }

    public function attributeLabels() {
        return [
            'code' => Yii::t('app', 'Code'),
            'en' => Yii::t('app', 'En'),
            'de' => Yii::t('app', 'De'),
            'es' => Yii::t('app', 'Es'),
            'fr' => Yii::t('app', 'Fr'),
            'it' => Yii::t('app', 'It'),
            'ru' => Yii::t('app', 'Ru'),
        ];
    }

}
