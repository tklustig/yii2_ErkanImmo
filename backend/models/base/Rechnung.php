<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "rechnung".
 *
 * @property integer $id
 * @property string $datumerstellung
 * @property string $datumfaellig
 * @property string $beschreibung
 * @property string $vorlage
 * @property string $geldbetrag
 * @property integer $mwst_id
 * @property integer $kunde_id
 * @property integer $makler_id
 * @property integer $kopf_id
 * @property integer $rechungsart_id
 * @property string $rechnungPlain
 * @property integer $aktualisiert_von
 * @property integer $angelegt_von
 * @property string $aktualisiert_am
 * @property string $angelegt_am
 *
 * @property \backend\models\Kopf $kopf
 * @property \backend\models\Kunde $kunde
 * @property \backend\models\User $makler
 * @property \backend\models\LMwst $mwst
 * @property \backend\models\User $angelegtVon
 * @property \backend\models\User $aktualisiertVon
 * @property \backend\models\LRechnungsart $rechungsart
 */
class Rechnung extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;


    /**
    * This function helps \mootensai\relation\RelationTrait runs faster
    * @return array relation names of this model
    */
    public function relationNames()
    {
        return [
            'kopf',
            'kunde',
            'makler',
            'mwst',
            'angelegtVon',
            'aktualisiertVon',
            'rechungsart'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['datumerstellung', 'datumfaellig', 'geldbetrag', 'kunde_id', 'makler_id', 'rechnungPlain'], 'required'],
            [['datumerstellung', 'datumfaellig', 'aktualisiert_am', 'angelegt_am'], 'safe'],
            [['beschreibung', 'vorlage', 'rechnungPlain'], 'string'],
            [['geldbetrag'], 'number'],
            [['mwst_id', 'kunde_id', 'makler_id', 'kopf_id', 'rechungsart_id', 'aktualisiert_von', 'angelegt_von'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rechnung';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'datumerstellung' => Yii::t('app', 'Datumerstellung'),
            'datumfaellig' => Yii::t('app', 'Datumfaellig'),
            'beschreibung' => Yii::t('app', 'Beschreibung'),
            'vorlage' => Yii::t('app', 'Vorlage'),
            'geldbetrag' => Yii::t('app', 'Geldbetrag'),
            'mwst_id' => Yii::t('app', 'Mwst ID'),
            'kunde_id' => Yii::t('app', 'Kunde ID'),
            'makler_id' => Yii::t('app', 'Makler ID'),
            'kopf_id' => Yii::t('app', 'Kopf ID'),
            'rechungsart_id' => Yii::t('app', 'Rechungsart ID'),
            'rechnungPlain' => Yii::t('app', 'Rechnung Plain'),
            'aktualisiert_von' => Yii::t('app', 'Aktualisiert Von'),
            'angelegt_von' => Yii::t('app', 'Angelegt Von'),
            'aktualisiert_am' => Yii::t('app', 'Aktualisiert Am'),
            'angelegt_am' => Yii::t('app', 'Angelegt Am'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKopf()
    {
        return $this->hasOne(\backend\models\Kopf::className(), ['id' => 'kopf_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKunde()
    {
        return $this->hasOne(\backend\models\Kunde::className(), ['id' => 'kunde_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMakler()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'makler_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMwst()
    {
        return $this->hasOne(\backend\models\LMwst::className(), ['id' => 'mwst_id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAngelegtVon()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'angelegt_von']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAktualisiertVon()
    {
        return $this->hasOne(\backend\models\User::className(), ['id' => 'aktualisiert_von']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRechungsart()
    {
        return $this->hasOne(\backend\models\LRechnungsart::className(), ['id' => 'rechungsart_id']);
    }
    
    /**
     * @inheritdoc
     * @return array mixed
     */
    public function behaviors()
    {
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
