<?php

namespace frontend\models\base;

use Yii;

/**
 * This is the base model class for table "adminbesichtigungkunde".
 *
 * @property integer $id
 * @property integer $besichtigungstermin_id
 * @property integer $admin_id
 * @property integer $kunde_id
 *
 * @property \frontend\models\User $admin
 * @property \frontend\models\Besichtigungstermin $besichtigungstermin
 * @property \frontend\models\Kunde $kunde
 */
class Adminbesichtigungkunde extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'admin',
            'besichtigungstermin',
            'kunde'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['besichtigungstermin_id', 'admin_id', 'kunde_id'], 'required'],
            [['besichtigungstermin_id', 'admin_id', 'kunde_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adminbesichtigungkunde';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'besichtigungstermin_id' => Yii::t('app', 'Besichtigungstermin ID'),
            'admin_id' => Yii::t('app', 'Admin ID'),
            'kunde_id' => Yii::t('app', 'Kunde ID'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(\frontend\models\User::className(), ['id' => 'admin_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBesichtigungstermin()
    {
        return $this->hasOne(\frontend\models\Besichtigungstermin::className(), ['id' => 'besichtigungstermin_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKunde()
    {
        return $this->hasOne(\frontend\models\Kunde::className(), ['id' => 'kunde_id']);
    }
    }
