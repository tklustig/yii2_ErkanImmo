<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "besichtigungstermin".
 *
 * @property integer $id
 * @property integer $l_plz_id
 * @property string $strasse
 * @property string $uhrzeit
 * @property integer $Relevanz
 * @property integer $l_stadt_id
 * @property string $angelegt_am
 * @property string $aktualisiert_am
 * @property integer $angelegt_von
 * @property integer $aktualisiert_von
 * @property integer $Immobilien_id
 *
 * @property \frontend\models\Adminbesichtigungkunde[] $adminbesichtigungkundes
 * @property \frontend\models\User $angelegtVon
 * @property \frontend\models\User $aktualisiertVon
 * @property \frontend\models\Immobilien $immobilien
 * @property \frontend\models\LStadt $lStadt
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
            'angelegtVon',
            'aktualisiertVon',
            'immobilien',
            'lStadt'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['l_plz_id', 'strasse', 'uhrzeit', 'l_stadt_id', 'Immobilien_id'], 'required'],
            [['l_plz_id', 'l_stadt_id', 'angelegt_von', 'aktualisiert_von', 'Immobilien_id'], 'integer'],
            [['uhrzeit', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['strasse'], 'string', 'max' => 64],
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
            'l_plz_id' => Yii::t('app', 'L Plz ID'),
            'strasse' => Yii::t('app', 'Strasse'),
            'uhrzeit' => Yii::t('app', 'Uhrzeit'),
            'Relevanz' => Yii::t('app', 'Relevanz'),
            'l_stadt_id' => Yii::t('app', 'L Stadt ID'),
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
    public function getAngelegtVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'angelegt_von']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAktualisiertVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'aktualisiert_von']);
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
    public function getLStadt() {
        return $this->hasOne(\frontend\models\LStadt::className(), ['id' => 'l_stadt_id']);
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

}
