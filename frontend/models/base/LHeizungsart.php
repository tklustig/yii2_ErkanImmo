<?php

namespace frontend\models\base;

use Yii;

class LHeizungsart extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'immobiliens'
        ];
    }

    public function rules() {
        return [
            [['bezeichnung'], 'required'],
            [['bezeichnung'], 'string', 'max' => 255]
        ];
    }

    public static function tableName() {
        return 'l_heizungsart';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bezeichnung' => Yii::t('app', 'Bezeichnung'),
        ];
    }

    public function getImmobiliens() {
        return $this->hasMany(\frontend\models\Immobilien::className(), ['l_heizungsart_id' => 'id']);
    }

}
