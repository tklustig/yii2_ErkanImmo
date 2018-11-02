<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Immobilien;
use frontend\models\ImmobilienSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use kartik\widgets\Growl;
use frontend\models\EDateianhang;
use frontend\models\Dateianhang;

/**
 * ImmobilienController implements the CRUD actions for Immobilien model.
 */
class ImmobilienController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionPreview($SearchPreview = NULL) {
        //ToDo:$count liefert mitunter einen inkorrekten Wert. Code überprüfen und korrigieren!
        $bmp = '/bmp/';
        $tif = '/tif/';
        $png = '/png/';
        $psd = '/psd/';
        $pcx = '/pcx/';
        $gif = '/gif/';
        $jpeg = '/jpeg/';
        $jpg = '/jpg/';
        $ico = '/ico/';
        $SearchModel = new ImmobilienSearch();
        $ArrayOfFilename = array();
        $ArrayOfId = array();
        $ArrayOfImmo = array();
        $ArrayOfE = array();
        $ArrayOfImmoAll = array();
        $ModelDateianhang = Dateianhang::find()->all();
        $ModelE = EDateianhang::find()->all();
        $ModelImmobilien = Immobilien::find()->all();
        // Eruiere zunächst alle Immobilien in der Datenbank
        foreach ($ModelE as $id) {
            array_push($ArrayOfE, $id->immobilien_id);
        }
        foreach ($ModelImmobilien as $id) {
            array_push($ArrayOfImmoAll, $id->id);
        }
        // Eruiere die jenigen Immobilien-Ids, die kein Bild haben
        $ArrayOfDifference = array_diff($ArrayOfImmoAll, $ArrayOfE);

        //verfrachte den Dateinamen des jeweiligen Bildes...
        foreach ($ModelDateianhang as $filename) {
            if (preg_match($bmp, $filename->dateiname) || preg_match($tif, $filename->dateiname) || preg_match($png, $filename->dateiname) || preg_match($psd, $filename->dateiname) || preg_match($pcx, $filename->dateiname) || preg_match($gif, $filename->dateiname) || preg_match($jpeg, $filename->dateiname) || preg_match($jpg, $filename->dateiname) || preg_match($ico, $filename->dateiname)) {
                array_push($ArrayOfFilename, $filename->dateiname);
                array_push($ArrayOfId, $filename->e_dateianhang_id);
            }
        }
        //...und dessen ID in Arrays
        for ($i = 0; $i < count($ArrayOfId); $i++) {
            array_push($ArrayOfImmo, EDateianhang::findOne(['id' => $ArrayOfId[$i]])->immobilien_id);
        }
        //zähle alle gefundenen Datensätze
        $count = Immobilien::find()->count();
        //intialisiere Arrays
        $ArrayOfArt = array();
        $ArrayOfMoney = array();
        $ArrayOfTown = array();
        $ArrayOfGroesse = array();
        $ArrayOfRooms = array();
        $ArrayOfPlz = array();
        $ArrayOfStreet = array();
        //eruiere alle Attribute, die angezeigt werden und verfrachte sie in obige Arrays
        foreach ($ModelImmobilien as $immoAttribute) {
            if ($immoAttribute->l_art_id == 2 && !in_array($immoAttribute->id, $ArrayOfDifference)) {
                $begriff = "Kaufpreis";
                array_push($ArrayOfArt, $begriff);
                $betrag = number_format(
                        $immoAttribute->geldbetrag, // zu konvertierende zahl
                        2, // Anzahl an Nochkommastellen
                        ",", // Dezimaltrennzeichen
                        "."    // 1000er-Trennzeichen
                );
                array_push($ArrayOfMoney, $betrag);
                array_push($ArrayOfPlz, $immoAttribute->lPlz->plz);
                array_push($ArrayOfTown, $immoAttribute->stadt);
                array_push($ArrayOfStreet, $immoAttribute->strasse);
                array_push($ArrayOfGroesse, $immoAttribute->wohnflaeche);
                array_push($ArrayOfRooms, $immoAttribute->raeume);
            } else if ($immoAttribute->l_art_id == 1 && !in_array($immoAttribute->id, $ArrayOfDifference)) {
                $begriff = "Kaltmiete";
                array_push($ArrayOfArt, $begriff);
                $betrag = number_format(
                        $immoAttribute->geldbetrag, // zu konvertierende zahl
                        2, // Anzahl an Nochkommastellen
                        ",", // Dezimaltrennzeichen
                        "."    // 1000er-Trennzeichen
                );
                array_push($ArrayOfMoney, $betrag);
                array_push($ArrayOfPlz, $immoAttribute->lPlz->plz);
                array_push($ArrayOfTown, $immoAttribute->stadt);
                array_push($ArrayOfStreet, $immoAttribute->strasse);
                array_push($ArrayOfGroesse, $immoAttribute->wohnflaeche);
                array_push($ArrayOfRooms, $immoAttribute->raeume);
            }
        }
        //sofern ein Suchrequest abgefeuert wurde,übergebe an das Searchmodel den Parameter...
        if ($SearchPreview == 1) {
            /* Hier noch prüfen, ob dataProvider nur Null-Werte enthält. Wenn ja, dann 'zurück rendern' */
            $DataProvider = $SearchModel->search(Yii::$app->request->queryParams, NULL, NULL, $SearchPreview);
            if ($DataProvider['plz'][0] == null && $DataProvider['Kosten'][0] == null && $DataProvider['raeume'][0] == null) {
                $DataProvider = $SearchModel->search(NULL, NULL, NULL, NULL);
                return $this->render('_index', [
                            'count' => $count,
                            'ArrayOfFilename' => $ArrayOfFilename,
                            'ArrayOfImmo' => $ArrayOfImmo,
                            'ArrayOfDifference' => $ArrayOfDifference,
                            'model_immobilien' => $ModelImmobilien,
                            'ArrayOfArt' => $ArrayOfArt,
                            'ArrayOfMoney' => $ArrayOfMoney,
                            'ArrayOfPlz' => $ArrayOfPlz,
                            'ArrayOfTown' => $ArrayOfTown,
                            'ArrayOfStreet' => $ArrayOfStreet,
                            'ArrayOfGroesse' => $ArrayOfGroesse,
                            'ArrayOfRooms' => $ArrayOfRooms,
                            'searchModel' => $SearchModel,
                            'dataProvider' => $DataProvider,
                ]);
            }

            //sofern Suchangaben unvollständig
            if ($DataProvider['operator'][0] == null && $DataProvider['Kosten'][0] != null) {
                ?><?=
                Growl::widget([
                    'type' => Growl::TYPE_GROWL,
                    'title' => 'Warning',
                    'icon' => 'glyphicon glyphicon-ok-sign',
                    'body' => 'Wenn Sie nach einem Kaufpreis/Miete suchen, müssen Sie entweder Höher als oder Weniger als auswählen.',
                    'showSeparator' => true,
                    'delay' => 1500,
                    'pluginOptions' => [
                        'showProgressbar' => true,
                        'placement' => [
                            'from' => 'top',
                            'align' => 'center',
                        ]
                    ]
                ]);
                $DataProvider = $SearchModel->search(NULL, NULL, NULL, NULL);
                return $this->render('_index', [
                            'count' => $count,
                            'ArrayOfFilename' => $ArrayOfFilename,
                            'ArrayOfImmo' => $ArrayOfImmo,
                            'ArrayOfDifference' => $ArrayOfDifference,
                            'model_immobilien' => $ModelImmobilien,
                            'ArrayOfArt' => $ArrayOfArt,
                            'ArrayOfMoney' => $ArrayOfMoney,
                            'ArrayOfPlz' => $ArrayOfPlz,
                            'ArrayOfTown' => $ArrayOfTown,
                            'ArrayOfStreet' => $ArrayOfStreet,
                            'ArrayOfGroesse' => $ArrayOfGroesse,
                            'ArrayOfRooms' => $ArrayOfRooms,
                            'searchModel' => $SearchModel,
                            'dataProvider' => $DataProvider,
                ]);
            }
            //lösche alle Arrays, sofern ein Suchrequest abgefeuert wurde
            $ArrayOfFilename = array();
            $ArrayOfId = array();
            $ArrayOfImmo = array();
            $ArrayOfImmoAll = array();
            $ArrayOfEDatei = array();
            $ArrayOfArt = array();
            $ArrayOfMoney = array();
            $ArrayOfTown = array();
            $ArrayOfGroesse = array();
            $ArrayOfRooms = array();
            $ArrayOfPlz = array();
            $ArrayOfStreet = array();
            $ArrayOfDifference = array();
            //füge neue Arrays hinzu
            $ArrayOfObjAnh = array();
            $ArrayOfObjImmo = array();
            /* bei 2^3 Suchparameter muss es folglich 2^3-1 Konditionen geben */
            //1.Kondition:Sofern Suchrequestparameter enthält plz
            if ($DataProvider['plz'][0] != null) {
                $model_I = Immobilien::find()->where(['l_plz_id' => $DataProvider['plz'][0]])->all();
            }
            //2.Kondition:Sofern Suchrequestparameter enthält Kohle
            if ($DataProvider['Kosten'][0] != null) {
                $operator = $DataProvider['operator'][0];
                $model_I = Immobilien::find()->where(["$operator", 'geldbetrag', $DataProvider['Kosten'][0]])->all();
            }
            //3.Kondition:Sofern Suchrequestparameter enthält Räume
            if ($DataProvider['raeume'][0] != null) {
                $model_I = Immobilien::find()->where([">=", 'raeume', $DataProvider['raeume'][0]])->all();
            }
            //4.Kondition:Sofern Suchrequestparameter enthält plz und Kohle
            if ($DataProvider['plz'][0] != null && $DataProvider['Kosten'][0] != null) {
                $model_I = Immobilien::find()->where(['l_plz_id' => $DataProvider['plz'][0]])->andWhere(["$operator", 'geldbetrag', $DataProvider['Kosten'][0]])->all();
            }
            //5.Kondition:Sofern Suchrequestparameter enthält plz und Räume
            if ($DataProvider['plz'][0] != null && $DataProvider['raeume'][0] != null) {
                $model_I = Immobilien::find()->where(['l_plz_id' => $DataProvider['plz'][0]])->andWhere([">=", 'raeume', $DataProvider['raeume'][0]])->all();
            }
            //6.Kondition:Sofern Suchrequestparameter enthält Kohle und Räume
            if ($DataProvider['Kosten'][0] != null && $DataProvider['raeume'][0] != null) {
                $model_I = Immobilien::find()->where(["$operator", 'geldbetrag', $DataProvider['Kosten'][0]])->andWhere([">=", 'raeume', $DataProvider['raeume'][0]])->all();
            }
            //7.Kondition:Sofern Suchrequestparameter enthält Kohle und Räume und plz
            if ($DataProvider['Kosten'][0] != null && $DataProvider['raeume'][0] != null && $DataProvider['plz'][0] != null) {
                $model_I = Immobilien::find()->where(['l_plz_id' => $DataProvider['plz'][0]])->andWhere(["$operator", 'geldbetrag', $DataProvider['Kosten'][0]])->andWhere([">=", 'raeume', $DataProvider['raeume'][0]])->all();
            }
            if (!empty($model_I)) {
                foreach ($model_I as $value) {
                    array_push($ArrayOfImmo, $value->id);
                }
                $count = count($ArrayOfImmo);
                $ArrayOfDifference = array_diff($ArrayOfImmo, $ArrayOfE);
                $count += count($ArrayOfDifference);
            }
            if (!empty($ArrayOfImmo)) {
                for ($i = 0; $i < count($ArrayOfImmo); $i++) {
                    if (!empty(EDateianhang::findOne(['immobilien_id' => $ArrayOfImmo[$i]]))) {
                        array_push($ArrayOfEDatei, EDateianhang::findOne(['immobilien_id' => $ArrayOfImmo[$i]])->id);
                    }
                }
                for ($i = 0; $i < count($ArrayOfEDatei); $i++) {
                    array_push($ArrayOfObjAnh, Dateianhang::find()->where(['e_dateianhang_id' => $ArrayOfEDatei[$i]])->all());
                    // if (preg_match($bmp, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfEDatei[$i]])->dateiname) || preg_match($tif, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfEDatei[$i]])->dateiname) || preg_match($png, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfEDatei[$i]])->dateiname) || preg_match($psd, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfEDatei[$i]])->dateiname) || preg_match($pcx, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfEDatei[$i]])->dateiname) || preg_match($gif, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfEDatei[$i]])->dateiname) || preg_match($jpeg, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfEDatei[$i]])->dateiname) || preg_match($jpg, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfEDatei[$i]])->dateiname) || preg_match($ico, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfEDatei[$i]])->dateiname)) {
                    //}
                }
                foreach ($ArrayOfObjAnh as $object) {
                    foreach ($object as $value) {
                        if (preg_match($bmp, $value->dateiname) || preg_match($tif, $value->dateiname) || preg_match($png, $value->dateiname) || preg_match($psd, $value->dateiname) || preg_match($pcx, $value->dateiname) || preg_match($gif, $value->dateiname) || preg_match($jpeg, $value->dateiname) || preg_match($jpg, $value->dateiname) || preg_match($ico, $value->dateiname)) {
                            array_push($ArrayOfFilename, $value->dateiname);
                        }
                    }
                }
                for ($i = 0; $i < count($ArrayOfImmo); $i++) {
                    array_push($ArrayOfObjImmo, Immobilien::find()->where(['id' => $ArrayOfImmo[$i]])->all());
                }
                foreach ($ArrayOfObjImmo as $object) {
                    foreach ($object as $value) {
                        if ($value->l_art_id == 2 && !in_array($value->id, $ArrayOfDifference)) {
                            $begriff = "Kaufpreis";
                            array_push($ArrayOfArt, $begriff);
                            $betrag = number_format(
                                    $value->geldbetrag, // zu konvertierende zahl
                                    2, // Anzahl an Nochkommastellen
                                    ",", // Dezimaltrennzeichen
                                    "."    // 1000er-Trennzeichen
                            );
                            array_push($ArrayOfMoney, $betrag);
                            array_push($ArrayOfPlz, $value->lPlz->plz);
                            array_push($ArrayOfTown, $value->stadt);
                            array_push($ArrayOfStreet, $value->strasse);
                            array_push($ArrayOfGroesse, $value->wohnflaeche);
                            array_push($ArrayOfRooms, $value->raeume);
                        } else if ($value->l_art_id == 1 && !in_array($value->id, $ArrayOfDifference)) {
                            $begriff = "Kaltmiete";
                            array_push($ArrayOfArt, $begriff);
                            $betrag = number_format(
                                    $value->geldbetrag, // zu konvertierende zahl
                                    2, // Anzahl an Nochkommastellen
                                    ",", // Dezimaltrennzeichen
                                    "."    // 1000er-Trennzeichen
                            );
                            array_push($ArrayOfMoney, $betrag);
                            array_push($ArrayOfPlz, $value->lPlz->plz);
                            array_push($ArrayOfTown, $value->stadt);
                            array_push($ArrayOfStreet, $value->strasse);
                            array_push($ArrayOfGroesse, $value->wohnflaeche);
                            array_push($ArrayOfRooms, $value->raeume);
                        }
                    }
                }
            }
            return $this->render('_index', [
                        'count' => $count,
                        'ArrayOfFilename' => $ArrayOfFilename,
                        'ArrayOfImmo' => $ArrayOfImmo,
                        'ArrayOfDifference' => $ArrayOfDifference,
                        'model_immobilien' => $ModelImmobilien,
                        'ArrayOfArt' => $ArrayOfArt,
                        'ArrayOfMoney' => $ArrayOfMoney,
                        'ArrayOfPlz' => $ArrayOfPlz,
                        'ArrayOfTown' => $ArrayOfTown,
                        'ArrayOfStreet' => $ArrayOfStreet,
                        'ArrayOfGroesse' => $ArrayOfGroesse,
                        'ArrayOfRooms' => $ArrayOfRooms,
                        'searchModel' => $SearchModel,
                        'dataProvider' => $DataProvider,
            ]);
            //andernfalls übergebe keine Parameter
        } else {
            $DataProvider = $SearchModel->search(NULL, NULL, NULL, NULL);
            return $this->render('_index', [
                        'count' => $count,
                        'ArrayOfFilename' => $ArrayOfFilename,
                        'ArrayOfImmo' => $ArrayOfImmo,
                        'ArrayOfDifference' => $ArrayOfDifference,
                        'model_immobilien' => $ModelImmobilien,
                        'ArrayOfArt' => $ArrayOfArt,
                        'ArrayOfMoney' => $ArrayOfMoney,
                        'ArrayOfPlz' => $ArrayOfPlz,
                        'ArrayOfTown' => $ArrayOfTown,
                        'ArrayOfStreet' => $ArrayOfStreet,
                        'ArrayOfGroesse' => $ArrayOfGroesse,
                        'ArrayOfRooms' => $ArrayOfRooms,
                        'searchModel' => $SearchModel,
                        'dataProvider' => $DataProvider,
            ]);
        }
    }

    public function actionIndex($id) {
        $art = Immobilien::findOne(['id' => $id])->l_art_id;
        $SearchModel = new ImmobilienSearch();
        $DataProvider = $SearchModel->search(Yii::$app->request->queryParams, $id, $art);
        return $this->render('index', [
                    'searchModel' => $SearchModel,
                    'dataProvider' => $DataProvider,
                    'art' => $art
        ]);
    }

    protected function findModel($id) {
        if (($model = Immobilien::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionTermin() {
        ?>
        <h3>
            Diese Methode soll dem Interessenten die Möglichkeit geben, einen Termin mit dem jeweiligen Makler zu beantragen. Es wird folglich ein Formular gerendert, welches die entsprechenden Optionen anbietet. Noch ist das allerdings eine Baustelle
        </h3><br>
        <?php
        print_r("Script in der Klasse " . get_class() . " angehalten");
        die();
    }

    public function actionShow($filename) {
        $completePath = Yii::getAlias('@pictures' . '/' . $filename);
        return Yii::$app->response->sendFile($completePath, $filename);
    }

    public function actionAuswahl($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('id, plz AS text')
                    ->from('l_plz')
                    ->where(['like', 'plz', $q]);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => LPlz::find($id)->plz];
        }
        return $out;
    }

}
