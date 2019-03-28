<?php

namespace backend\models\base;

use Yii;

class LMwst extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'rechnungs'
        ];
    }

    public function rules() {
        return [
            [['satz'], 'required'],
            [['satz'], 'number']
        ];
    }

    public static function tableName() {
        return 'l_mwst';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'satz' => Yii::t('app', 'Satz'),
        ];
    }

    public function getRechnungs() {
        return $this->hasMany(\backend\models\Rechnung::className(), ['mwst_id' => 'id']);
    }

}
