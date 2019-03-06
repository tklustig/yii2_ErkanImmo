<?php

namespace frontend\models\base;

use Yii;

class LDateianhangArt extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'dateianhangs'
        ];
    }

    public function rules() {
        return [
            [['bezeichnung'], 'required'],
            [['bezeichnung'], 'string', 'max' => 255]
        ];
    }

    public static function tableName() {
        return 'l_dateianhang_art';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bezeichnung' => Yii::t('app', 'Bezeichnung'),
        ];
    }

    public function getDateianhangs() {
        return $this->hasMany(\frontend\models\Dateianhang::className(), ['l_dateianhang_art_id' => 'id']);
    }

}
