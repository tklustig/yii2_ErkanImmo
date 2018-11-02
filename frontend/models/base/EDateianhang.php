<?php

namespace frontend\models\base;

use Yii;

/**
 * This is the base model class for table "e_dateianhang".
 *
 * @property integer $id
 * @property integer $immobilien_id
 * @property integer $user_id
 * @property integer $kunde_id
 *
 * @property \frontend\models\Dateianhang[] $dateianhangs
 * @property \frontend\models\User $user
 * @property \frontend\models\Immobilien $immobilien
 * @property \frontend\models\Kunde $kunde
 */
class EDateianhang extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames() {
        return [
            'dateianhangs',
            'user',
            'immobilien',
            'kunde'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['immobilien_id', 'user_id', 'kunde_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'e_dateianhang';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'immobilien_id' => Yii::t('app', 'Immobilien ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'kunde_id' => Yii::t('app', 'Kunde ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDateianhangs() {
        return $this->hasMany(\frontend\models\Dateianhang::className(), ['e_dateianhang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImmobilien() {
        return $this->hasOne(\frontend\models\Immobilien::className(), ['id' => 'immobilien_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKunde() {
        return $this->hasOne(\frontend\models\Kunde::className(), ['id' => 'kunde_id']);
    }

}
