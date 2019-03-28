<?php

namespace backend\models\base;

use Yii;

class Kopf extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'user',
            'rechnungs'
        ];
    }

    public function rules() {
        return [
            [['data', 'user_id'], 'required'],
            [['data'], 'string'],
            [['user_id'], 'integer']
        ];
    }

    public static function tableName() {
        return 'kopf';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'data' => Yii::t('app', 'Data'),
            'user_id' => Yii::t('app', 'UserID'),
        ];
    }

    public function getUser() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    public function getRechnungs() {
        return $this->hasMany(\backend\models\Rechnung::className(), ['kopf_id' => 'id']);
    }

}
