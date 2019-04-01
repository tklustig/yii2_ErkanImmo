<?php

namespace backend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class Rechnung extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

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

    public static function tableName()
    {
        return 'rechnung';
    }

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

    public function getKopf()
    {
        return $this->hasOne(\backend\models\Kopf::className(), ['id' => 'kopf_id']);
    }

    public function getKunde()
    {
        return $this->hasOne(\frontend\models\Kunde::className(), ['id' => 'kunde_id']);
    }

    public function getMakler()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'makler_id']);
    }

    public function getMwst()
    {
        return $this->hasOne(\backend\models\LMwst::className(), ['id' => 'mwst_id']);
    }

    public function getAngelegtVon()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'angelegt_von']);
    }

    public function getAktualisiertVon()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'aktualisiert_von']);
    }

    public function getRechungsart()
    {
        return $this->hasOne(\backend\models\LRechnungsart::className(), ['id' => 'rechungsart_id']);
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
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'angelegt_von',
                'updatedByAttribute' => 'aktualisiert_von',
            ],
        ];
    }
}
