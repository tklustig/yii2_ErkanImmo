<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "besichtigungstermin".
 *
 * @property integer $id
 * @property string $uhrzeit
 * @property integer $Relevanz
 * @property string $angelegt_am
 * @property string $aktualisiert_am
 * @property integer $angelegt_von
 * @property integer $aktualisiert_von
 * @property integer $Immobilien_id
 *
 * @property \frontend\models\Adminbesichtigungkunde[] $adminbesichtigungkundes
 * @property \frontend\models\Immobilien $immobilien
 * @property \frontend\models\User $angelegtVon
 * @property \frontend\models\User $aktualisiertVon
 */
class Besichtigungstermin extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames() {
        return [
            'adminbesichtigungkundes',
            'immobilien',
            'angelegtVon',
            'aktualisiertVon'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uhrzeit', 'Immobilien_id'], 'required'],
            [['uhrzeit', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['angelegt_von', 'aktualisiert_von', 'Immobilien_id'], 'integer'],
            [['Relevanz'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'besichtigungstermin';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminbesichtigungkundes() {
        return $this->hasMany(\frontend\models\Adminbesichtigungkunde::className(), ['besichtigungstermin_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImmobilien() {
        return $this->hasOne(\frontend\models\Immobilien::className(), ['id' => 'Immobilien_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAktualisiertVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'aktualisiert_von']);
    }

    public function getAngelegtVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'angelegt_von']);
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
            ]
        ];
    }

}
