<?php

namespace backend\models\base;

use Yii;

class LTextbaustein extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'mails'
        ];
    }

    public function rules() {
        return [
            [['beschreibung', 'data'], 'required'],
            [['data'], 'string'],
            [['beschreibung'], 'string', 'max' => 64]
        ];
    }

    public static function tableName() {
        return 'l_textbaustein';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'beschreibung' => Yii::t('app', 'Beschreibung'),
            'data' => Yii::t('app', 'Data'),
        ];
    }

    public function getMails() {
        return $this->hasMany(\backend\models\Mail::className(), ['textbaustein_id' => 'id']);
    }

}
