<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use frontend\models\EDateianhang;

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
            [['angelegt_am', 'aktualisert_am'], 'safe'],
            [['angelegt_von', 'aktualisiert_von', 'l_dateianhang_art_id', 'e_dateianhang_id'], 'integer'],
            [['bezeichnung', 'dateiname'], 'string'],
            [['dateiname', 'l_dateianhang_art_id', 'e_dateianhang_id'], 'required', 'except' => 'create_Dateianhang'],
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
            'angelegt_am' => Yii::t('app', 'angelegt am'),
            'aktualisert_am' => Yii::t('app', 'aktualisert am'),
            'angelegt_von' => Yii::t('app', 'angelegt von'),
            'aktualisiert_von' => Yii::t('app', 'aktualisiert von'),
            'l_dateianhang_art_id' => Yii::t('app', 'Dateianhangssrt'),
            'e_dateianhang_id' => Yii::t('app', 'e_dateianhang_id'),
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

    public function upload($model) {
        $x = 0;
        $valid = $this->validate();
        if (!$valid) {
            $error_dateianhang = $model->getErrors();
            foreach ($error_dateianhang as $values) {
                foreach ($values as $ausgabe) {
                    var_dump($ausgabe);
                }
            }
            print_r("Script in der Klasse " . get_class() . " angehalten");
            die();
        }
        foreach ($this->attachement as $uploaded_file) {
            //Umlaute im Dateinamen ersetzen
            $umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
            $ersetzen = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
            $uploaded_file->name = str_replace($umlaute, $ersetzen, $uploaded_file->name);
            $uploaded_file->saveAs(Yii::getAlias('@pictures') . "/" . $uploaded_file->name);
            $x++;
        }
        if ($x > 0) {
            return true;
        }
        return false;
    }

}
