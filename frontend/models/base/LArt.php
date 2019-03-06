<?php

namespace frontend\models\base;

use Yii;

class LArt extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'immobiliens'
        ];
    }

    public function rules() {
        return [
            [['bezeichnung'], 'required'],
            [['bezeichnung'], 'string', 'max' => 32]
        ];
    }

    public static function tableName() {
        return 'l_art';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bezeichnung' => Yii::t('app', 'Bezeichnung'),
        ];
    }

    public function getImmobiliens() {
        return $this->hasMany(\frontend\models\Immobilien::className(), ['l_art_id' => 'id']);
    }

}
