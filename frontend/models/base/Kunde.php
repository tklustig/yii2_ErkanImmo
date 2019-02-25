<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the base model class for table "kunde".
 *
 * @property integer $id
 * @property integer $l_plz_id
 * @property string $geschlecht
 * @property string $vorname
 * @property string $nachname
 * @property string $stadt
 * @property string $strasse
 * @property string $geburtsdatum
 * @property integer $solvenz
 * @property string $telefon
 * @property string $email
 * @property integer $bankverbindung_id
 * @property string $angelegt_am
 * @property string $aktualisiert_am
 * @property integer $angelegt_von
 * @property integer $aktualisiert_von
 *
 * @property \frontend\models\Adminbesichtigungkunde[] $adminbesichtigungkundes
 * @property \frontend\models\EDateianhang[] $eDateianhangs
 * @property \frontend\models\Bankverbindung $bankverbindung
 * @property \frontend\models\User $aktualisiertVon
 * @property \frontend\models\Kundeimmobillie[] $kundeimmobillies
 */
class Kunde extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    /**
     * This function helps \mootensai\relation\RelationTrait runs faster
     * @return array relation names of this model
     */
    public function relationNames() {
        return [
            'adminbesichtigungkundes',
            'eDateianhangs',
            'bankverbindung',
            'aktualisiertVon',
            'kundeimmobillies'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['l_plz_id', 'geschlecht', 'vorname', 'nachname', 'stadt', 'strasse'], 'required'],
            [['l_plz_id', 'bankverbindung_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['geburtsdatum', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            ['email', 'email'],
            [['geschlecht'], 'string', 'max' => 64],
            [['vorname', 'nachname', 'stadt'], 'string', 'max' => 255],
            [['strasse'], 'string', 'max' => 44],
            [['solvenz'], 'string', 'max' => 1],
            ['telefon', 'filter', 'filter' => function ($value) {
                    return $value;
                }],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'kunde';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'l_plz_id' => 'L Plz ID',
            'geschlecht' => 'Geschlecht',
            'vorname' => 'Vorname',
            'nachname' => 'Nachname',
            'stadt' => 'Stadt',
            'strasse' => 'Strasse',
            'geburtsdatum' => 'Geburtsdatum',
            'solvenz' => 'Solvenz',
            'telefon' => 'Telefon',
            'email' => 'Email',
            'bankverbindung_id' => 'Bankverbindung ID',
            'angelegt_am' => 'Angelegt Am',
            'aktualisiert_am' => 'Aktualisiert Am',
            'angelegt_von' => 'Angelegt Von',
            'aktualisiert_von' => 'Aktualisiert Von',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdminbesichtigungkundes() {
        return $this->hasMany(\frontend\models\Adminbesichtigungkunde::className(), ['kunde_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEDateianhangs() {
        return $this->hasMany(\frontend\models\EDateianhang::className(), ['kunde_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankverbindung() {
        return $this->hasOne(\frontend\models\Bankverbindung::className(), ['id' => 'bankverbindung_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAktualisiertVon() {
        return $this->hasOne(\frontend\models\User::className(), ['id' => 'aktualisiert_von']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKundeimmobillies() {
        return $this->hasMany(\frontend\models\Kundeimmobillie::className(), ['kunde_id' => 'id']);
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
