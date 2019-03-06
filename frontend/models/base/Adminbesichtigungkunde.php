<?php

namespace frontend\models\base;

use Yii;

class Adminbesichtigungkunde extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public function relationNames() {
        return [
            'admin',
            'besichtigungstermin',
            'kunde'
        ];
    }

    public function rules() {
        return [
            [['besichtigungstermin_id', 'admin_id', 'kunde_id'], 'required'],
            [['besichtigungstermin_id', 'admin_id', 'kunde_id'], 'integer']
        ];
    }

    public static function tableName() {
        return 'adminbesichtigungkunde';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'besichtigungstermin_id' => Yii::t('app', 'Besichtigungstermin ID'),
            'admin_id' => Yii::t('app', 'Admin ID'),
            'kunde_id' => Yii::t('app', 'Kunde ID'),
        ];
    }

    public function getAdmin() {
        return $this->hasOne(\frontend\models\User::className(), ['id' => 'admin_id']);
    }

    public function getBesichtigungstermin() {
        return $this->hasOne(\frontend\models\Besichtigungstermin::className(), ['id' => 'besichtigungstermin_id']);
    }

    public function getKunde() {
        return $this->hasOne(\frontend\models\Kunde::className(), ['id' => 'kunde_id']);
    }

}
