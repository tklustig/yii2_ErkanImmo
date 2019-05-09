<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;


class Besichtigungstermin extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    public function relationNames()
    {
        return [
            'adminbesichtigungkundes',
            'immobilien',
            'angelegtVon'
        ];
    }


    public function rules()
    {
        return [
            [['uhrzeit', 'Immobilien_id'], 'required'],
            [['uhrzeit', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['angelegt_von', 'aktualisiert_von', 'Immobilien_id'], 'integer'],
            [['Relevanz'], 'boolean']
        ];
    }


    public static function tableName()
    {
        return 'besichtigungstermin';
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uhrzeit' => Yii::t('app', 'Uhrzeit'),
            'Relevanz' => Yii::t('app', 'Relevanz'),
            'angelegt_am' => Yii::t('app', 'Angelegt Am'),
            'aktualisiert_am' => Yii::t('app', 'Aktualisiert Am'),
            'angelegt_von' => Yii::t('app', 'Angelegt Von'),
            'aktualisiert_von' => Yii::t('app', 'Aktualisiert Von'),
            'Immobilien_id' => Yii::t('app', 'Immobilien ID'),
        ];
    }

    public function getAdminbesichtigungkundes()
    {
        return $this->hasMany(\frontend\models\Adminbesichtigungkunde::className(), ['besichtigungstermin_id' => 'id']);
    }

    public function getImmobilien()
    {
        return $this->hasOne(\frontend\models\Immobilien::className(), ['id' => 'Immobilien_id']);
    }

    public function getAktualisiertVon()
    {
        return $this->hasOne(\frontend\models\Kunde::className(), ['id' => 'aktualisiert_von']);
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'angelegt_am',
                'updatedAtAttribute' => 'aktualisiert_am',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }
}
