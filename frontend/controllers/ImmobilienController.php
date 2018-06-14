<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Immobilien;
use frontend\models\ImmobilienSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
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

    public function actionPreview() {
        $bmp = '/bmp/';
        $tif = '/tif/';
        $png = '/png/';
        $psd = '/psd/';
        $pcx = '/pcx/';
        $gif = '/gif/';
        $jpeg = '/jpeg/';
        $jpg = '/jpg/';
        $ico = '/ico/';
        $searchModel = new ImmobilienSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $ArrayOfFilename = array();
        $ArrayOfId = array();
        $ArrayOfImmo = array();
        $ArrayOfE = array();
        $ArrayOfImmoAll = array();
        $model_dateianhang = Dateianhang::find()->all();
        $model_e = EDateianhang::find()->all();
        $model_immobilien = Immobilien::find()->all();
// Eruiere die jenigen Immobilien-Ids, die kein Bild haben
        foreach ($model_e as $id) {
            array_push($ArrayOfE, $id->immobilien_id);
        }
        foreach ($model_immobilien as $id) {
            array_push($ArrayOfImmoAll, $id->id);
        }
//verfrachte diese Id in ein Array
        $ArrayOfDifference = array_diff($ArrayOfImmoAll, $ArrayOfE);

//Eruiere alle Immobilien-Ids, die ein Bild haben
//verfrachte den Dateinamen des jeweiligen Bildes...
        foreach ($model_dateianhang as $filename) {
            if (preg_match($bmp, $filename->dateiname) || preg_match($tif, $filename->dateiname) || preg_match($png, $filename->dateiname) || preg_match($psd, $filename->dateiname) || preg_match($pcx, $filename->dateiname) || preg_match($gif, $filename->dateiname) || preg_match($jpeg, $filename->dateiname) || preg_match($jpg, $filename->dateiname) || preg_match($ico, $filename->dateiname)) {
                array_push($ArrayOfFilename, $filename->dateiname);
                array_push($ArrayOfId, $filename->e_dateianhang_id);
            }
        }
//und dessen ID in Arrays
        for ($i = 0; $i < count($ArrayOfId); $i++) {
            array_push($ArrayOfImmo, EDateianhang::findOne(['id' => $ArrayOfId[$i]])->immobilien_id);
        }
//zähle alle gefundenen Datensätze
        $count = Immobilien::find()->count();
//eruiere alle Attribute, die angezeigt werden und verfrachte sie in ein Array
        $ArrayOfArt = array();
        $ArrayOfMoney = array();
        $ArrayOfTown = array();
        $ArrayOfGroesse = array();
        $ArrayOfRooms = array();
        $ArrayOfPlz = array();
        $ArrayOfStreet = array();
        foreach ($model_immobilien as $immoAttribute) {
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
        //übergebe folgende Werte:Array(kein Bild),Array(Bild),Array(Immo-Id), AnzahlDatensätze und alle Attributarrays
        return $this->render('_index', [
                    'count' => $count,
                    'ArrayOfFilename' => $ArrayOfFilename,
                    'ArrayOfImmo' => $ArrayOfImmo,
                    'ArrayOfDifference' => $ArrayOfDifference,
                    'model_immobilien' => $model_immobilien,
                    'ArrayOfArt' => $ArrayOfArt,
                    'ArrayOfMoney' => $ArrayOfMoney,
                    'ArrayOfPlz' => $ArrayOfPlz,
                    'ArrayOfTown' => $ArrayOfTown,
                    'ArrayOfStreet' => $ArrayOfStreet,
                    'ArrayOfGroesse' => $ArrayOfGroesse,
                    'ArrayOfRooms' => $ArrayOfRooms,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex($id) {
        $art = Immobilien::findOne(['id' => $id])->l_art_id;
        $searchModel = new ImmobilienSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id, $art);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
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

}
