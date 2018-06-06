<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class Dateianhang extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public $attachement;

    public function relationNames() {
        return [
            'eDateianhang',
            'lDateianhangArt'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dateiname', 'angelegt_von', 'aktualisiert_von', 'l_dateianhang_art_id', 'e_dateianhang_id'], 'required'],
            [['angelegt_am', 'aktualisert_am'], 'safe'],
            [['angelegt_von', 'aktualisiert_von', 'l_dateianhang_art_id', 'e_dateianhang_id'], 'integer'],
            [['bezeichnung', 'dateiname'], 'string', 'max' => 255],
            [['attachement'], 'file', 'skipOnEmpty' => true, 'maxSize' => 10 * 1024000, 'tooBig' => 'Maximal erlaubte Dateigröße:10 MByte', 'maxFiles' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dateianhang';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bezeichnung' => Yii::t('app', 'Bezeichnung'),
            'dateiname' => Yii::t('app', 'Dateiname'),
            'angelegt_am' => Yii::t('app', 'Angelegt Am'),
            'aktualisert_am' => Yii::t('app', 'Aktualisert Am'),
            'angelegt_von' => Yii::t('app', 'Angelegt Von'),
            'aktualisiert_von' => Yii::t('app', 'Aktualisiert Von'),
            'l_dateianhang_art_id' => Yii::t('app', 'L Dateianhang Art ID'),
            'e_dateianhang_id' => Yii::t('app', 'E Dateianhang ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEDateianhang() {
        return $this->hasOne(\frontend\models\EDateianhang::className(), ['id' => 'e_dateianhang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLDateianhangArt() {
        return $this->hasOne(\frontend\models\LDateianhangArt::className(), ['id' => 'l_dateianhang_art_id']);
    }

    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'angelegt_am',
                'updatedAtAttribute' => 'aktualisiert_am',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'angelegt_von',
                'updatedByAttribute' => 'aktualisiert_von',
            ],
        ];
    }

    public static function GetBild($model) {
        try {
            return Dateianhang::find()
                            ->leftJoin('e_dateianhang', 'dateianhang.e_dateianhang_id =e_dateianhang.id')
                            ->leftJoin('immobilien', 'e_dateianhang.immobilien_id = immobilien.id')
                            ->where(['e_dateianhang.immobilien_id' => $model::findOne([$model->id])->id])->all();
        } catch (\Exception $error) {
            return;
        }
    }

}
