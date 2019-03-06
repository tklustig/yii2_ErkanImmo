<?php

namespace frontend\models\base;

use Yii;

class Kundeimmobillie extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return ['immobilien', 'kunde'];
    }

    public function rules() {
        return [
            [['kunde_id', 'immobilien_id'], 'required'],
            [['kunde_id', 'immobilien_id'], 'integer']
        ];
    }

    public static function tableName() {
        return 'kundeimmobillie';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'kunde_id' => Yii::t('app', 'Kunde ID'),
            'immobilien_id' => Yii::t('app', 'Immobilien ID'),
        ];
    }

    public function getImmobilien() {
        return $this->hasOne(\frontend\models\Immobilien::className(), ['id' => 'immobilien_id']);
    }

    public function getKunde() {
        return $this->hasOne(\frontend\models\Kunde::className(), ['id' => 'kunde_id']);
    }

}
