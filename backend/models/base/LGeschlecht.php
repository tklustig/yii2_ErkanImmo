<?php

namespace backend\models\base;

use Yii;

class LGeschlecht extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'kundes'
        ];
    }

    public function rules() {
        return [
            [['typus'], 'required'],
            [['typus'], 'string', 'max' => 16]
        ];
    }

    public static function tableName() {
        return 'l_geschlecht';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'typus' => Yii::t('app', 'Typus'),
        ];
    }

    public function getKundes() {
        return $this->hasMany(\backend\models\Kunde::className(), ['geschlecht' => 'id']);
    }

}
