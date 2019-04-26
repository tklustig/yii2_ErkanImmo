<?php

namespace frontend\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\web\NotAcceptableHttpException;

class Dateianhang extends \yii\db\ActiveRecord {

    use \mootensai\relation\RelationTrait;

    public $attachement;

    public function relationNames() {
        return [
            'eDateianhang',
            'lDateianhangArt',
            'angelegtVon',
            'aktualisiertVon'
        ];
    }

    public function rules() {
        return [
            [['angelegt_am', 'aktualisert_am'], 'safe'],
            [['angelegt_von', 'aktualisiert_von', 'l_dateianhang_art_id', 'e_dateianhang_id'], 'integer'],
            [['bezeichnung', 'dateiname'], 'string'],
            [['dateiname', 'l_dateianhang_art_id', 'e_dateianhang_id'], 'required', 'except' => 'create_Dateianhang'],
            [['attachement'], 'file', 'skipOnEmpty' => true, 'maxSize' => 2 * 1024 * 1024, 'tooBig' => 'Maximal erlaubte Dateigröße:2 MByte', 'maxFiles' => 10],
        ];
    }

    public static function tableName() {
        return 'dateianhang';
    }

    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bezeichnung' => Yii::t('app', 'Bezeichnung'),
            'dateiname' => Yii::t('app', 'Dateiname'),
            'angelegt_am' => Yii::t('app', 'angelegt am'),
            'aktualisert_am' => Yii::t('app', 'aktualisert am'),
            'angelegt_von' => Yii::t('app', 'angelegt von'),
            'aktualisiert_von' => Yii::t('app', 'aktualisiert von'),
            'l_dateianhang_art_id' => Yii::t('app', 'Dateianhangsart'),
            'e_dateianhang_id' => Yii::t('app', 'e_dateianhang_id'),
        ];
    }

    public function getEDateianhang() {
        return $this->hasOne(\frontend\models\EDateianhang::className(), ['id' => 'e_dateianhang_id']);
    }

    public function getLDateianhangArt() {
        return $this->hasOne(\frontend\models\LDateianhangArt::className(), ['id' => 'l_dateianhang_art_id']);
    }

    public function getAktualisiertVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'aktualisiert_von']);
    }

    public function getAngelegtVon() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'angelegt_von']);
    }

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

    public static function GetKundenBild($model) {
        try {
            return Dateianhang::find()
                            ->leftJoin('e_dateianhang', 'dateianhang.e_dateianhang_id =e_dateianhang.id')
                            ->leftJoin('kunde', 'e_dateianhang.kunde_id = kunde.id')
                            ->where(['e_dateianhang.kunde_id' => $model::findOne([$model->id])->id])->all();
        } catch (\Exception $error) {
            return;
        }
    }

    /*  wird derzeit nicht (mehr) genutzt. Verbleibt allerdings im Repositorie, da aufgezeigt wird, wie man in einer find()->leftJoin()
      ->where()-Klausel eine Relation auf einen Statischen Wert prüft(SELECT * FROM tbl1 JOIN tbl2 ON... where user_id>=1)
     */

    public static function GetTheme($model) {
        try {
            return Dateianhang::find()
                            ->leftJoin('e_dateianhang', 'dateianhang.e_dateianhang_id =e_dateianhang.id')
                            ->where(['>=', 'e_dateianhang.user_id', 1])->all();
        } catch (\Exception $error) {
            print_r("System Error. Bitte den Softwarehersteller kontaktieren:<br>$error");
            die();
        }
    }

    public function upload($model, $bool = NULL) {
        $x = 0;
        $valid = $this->validate();
        if (!$valid) {
            $errorDateianhang = $model->getErrors();
            foreach ($errorDateianhang as $values) {
                foreach ($values as $ausgabe) {
                    var_dump($ausgabe);
                    throw new NotAcceptableHttpException(Yii::t('app', $ausgabe));
                }
            }
        }
        foreach ($this->attachement as $uploadedFile) {
            $uploadedFile->name = $this->Ersetzen($uploadedFile->name);
            if ($bool != NULL && $bool == TRUE) {
                $uploadedFile->saveAs(Yii::getAlias('@documentsMail') . DIRECTORY_SEPARATOR . $uploadedFile->name);
            } else {
                if ($uploadedFile->extension == "pdf" || $uploadedFile->extension == "txt" || $uploadedFile->extension == "doc" || $uploadedFile->extension == "docx" || $uploadedFile->extension == "xls" || $uploadedFile->extension == "xlsx" || $uploadedFile->extension == "ppt" || $uploadedFile->extension == "odt") {
                    $uploadedFile->saveAs(Yii::getAlias('@documentsImmoB') . DIRECTORY_SEPARATOR . $uploadedFile->name);
                    copy(Yii::getAlias('@documentsImmoB') . DIRECTORY_SEPARATOR . $uploadedFile->name, Yii::getAlias('@documentsImmoF') . DIRECTORY_SEPARATOR . $uploadedFile->name);
                } else {
                    $uploadedFile->saveAs(Yii::getAlias('@picturesBackend') . DIRECTORY_SEPARATOR . $uploadedFile->name);
                    copy(Yii::getAlias('@picturesBackend') . DIRECTORY_SEPARATOR . $uploadedFile->name, Yii::getAlias('@pictures') . DIRECTORY_SEPARATOR . $uploadedFile->name);
                }
            }
            $x++;
        }
        if ($x > 0) {
            return true;
        }
        return false;
    }

    public function uploadBackendThemes($model) {
        $x = 0;
        $valid = $this->validate();
        if (!$valid) {
            $errorDateianhang = $model->getErrors();
            foreach ($errorDateianhang as $values) {
                foreach ($values as $ausgabe) {
                    var_dump($ausgabe);
                    throw new NotAcceptableHttpException(Yii::t('app', $ausgabe));
                }
            }
        }
        foreach ($this->attachement as $uploadedFile) {
            $endungen = array('jpg', 'jpeg', 'tiff', 'gif', 'bmp', 'png', 'svg', 'ico');
            for ($i = 0; $i < count($endungen); $i++) {
                if ($uploadedFile->extension != $endungen[$i])
                    $bool = false;
                else {
                    $bool = true;
                    break;
                }
            }
            if (!$bool)
                return false;
            $uploadedFile->name = $this->Ersetzen($uploadedFile->name);
            $uploadedFile->saveAs(Yii::getAlias('@uploading') . DIRECTORY_SEPARATOR . $uploadedFile->name);
            /*  Das Bild muss in das Webverzeichnis kopiert werden, damit es überhaupt angezeigt wird. Bilder in anderen Ordnern 
              werden prinzipiell nicht angezeigt */
            copy(Yii::getAlias('@uploading') . DIRECTORY_SEPARATOR . $uploadedFile->name, Yii::getAlias('@picturesBackend') . DIRECTORY_SEPARATOR . $uploadedFile->name);
            $x++;
        }
        if ($x > 0) {
            return true;
        }
        return false;
    }

    public function uploadBackend($model) {
        $x = 0;
        $valid = $this->validate();
        if (!$valid) {
            $errorDateianhang = $model->getErrors();
            foreach ($errorDateianhang as $values) {
                foreach ($values as $ausgabe) {
                    var_dump($ausgabe);
                    throw new NotAcceptableHttpException(Yii::t('app', $ausgabe));
                }
            }
        }
        foreach ($this->attachement as $uploadedFile) {
            $endungen = array('jpg', 'jpeg', 'tiff', 'gif', 'bmp', 'png', 'svg', 'ico');
            for ($i = 0; $i < count($endungen); $i++) {
                if ($uploadedFile->extension != $endungen[$i])
                    $bool = false;
                else {
                    $bool = true;
                    break;
                }
            }
            if (!$bool)
                return false;
            $uploadedFile->name = $this->Ersetzen($uploadedFile->name);
            $uploadedFile->saveAs(Yii::getAlias('@picturesBackend') . DIRECTORY_SEPARATOR . $uploadedFile->name);
            $x++;
        }
        if ($x > 0) {
            return true;
        }
        return false;
    }

    private function Ersetzen($string) {
        $umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
        $ersetzen = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
        $string = str_replace($umlaute, $ersetzen, $string);
        return $string;
    }

}
