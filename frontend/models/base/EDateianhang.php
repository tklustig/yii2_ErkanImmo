<?php

namespace frontend\models\base;

use Yii;

class EDateianhang extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'dateianhangs',
            'user',
            'immobilien',
            'kunde'
        ];
    }

    public function rules() {
        return [
            [['immobilien_id', 'user_id', 'kunde_id'], 'integer']
        ];
    }

    public static function tableName() {
        return 'e_dateianhang';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'immobilien_id' => Yii::t('app', 'Immobilien ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'kunde_id' => Yii::t('app', 'Kunde ID'),
        ];
    }

    public function getDateianhangs() {
        return $this->hasMany(\frontend\models\Dateianhang::className(), ['e_dateianhang_id' => 'id']);
    }

    public function getUser() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    public function getImmobilien() {
        return $this->hasOne(\frontend\models\Immobilien::className(), ['id' => 'immobilien_id']);
    }

    public function getKunde() {
        return $this->hasOne(\frontend\models\Kunde::className(), ['id' => 'kunde_id']);
    }

}
