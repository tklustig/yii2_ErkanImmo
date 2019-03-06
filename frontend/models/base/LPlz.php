<?php

namespace frontend\models\base;

use Yii;

class LPlz extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            ''
        ];
    }

    public function rules() {
        return [
            [['plz', 'ort', 'bl'], 'required'],
            [['plz'], 'string', 'max' => 5],
            [['ort', 'bl'], 'string', 'max' => 50]
        ];
    }

    public static function tableName() {
        return 'l_plz';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'plz' => Yii::t('app', 'Plz'),
            'ort' => Yii::t('app', 'Ort'),
            'bl' => Yii::t('app', 'Bl'),
        ];
    }

}
