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

    public function actionPreview($searchPreview = NULL) {
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
//...und dessen ID in Arrays
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
//sofern ein Suchrequest abgefeuert wurde,übergebe an das Searchmodel den Parameter...
        if ($searchPreview == 1) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, NULL, NULL, $searchPreview);
//sofern Suchangaben unvollständig
            if ($dataProvider['operator'][0] == null && $dataProvider['Kosten'][0] != null) {
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
                $dataProvider = $searchModel->search(NULL, NULL, NULL, NULL);
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
//lösche alle Arrays, sofern ein Suchrequest abgefeuert wurde

            $ArrayOfFilename = array();
            $ArrayOfId = array();
            $ArrayOfImmo = array();
            $ArrayOfE = array();
            $ArrayOfImmoAll = array();
            $ArrayOfArt = array();
            $ArrayOfMoney = array();
            $ArrayOfTown = array();
            $ArrayOfGroesse = array();
            $ArrayOfRooms = array();
            $ArrayOfPlz = array();
            $ArrayOfStreet = array();
            $ArrayOfDifference = array();
            $count = 0;
            /* bei 2^3 Suchparameter muss es folglich auch 2^3 Konditionen geben */
//1.Kondition:Sofern Suchrequestparameter enthält plz
            if ($dataProvider['plz'][0] != null) {
                $model_I = Immobilien::find()->where(['l_plz_id' => $dataProvider['plz'][0]])->all();
            }
//2.Kondition:Sofern Suchrequestparameter enthält Kohle
            if ($dataProvider['Kosten'][0] != null) {
                $operator = $dataProvider['operator'][0];
                $model_I = Immobilien::find()->where(["$operator", 'geldbetrag', $dataProvider['Kosten'][0]])->all();
            }
//3.Kondition:Sofern Suchrequestparameter enthält Räume
            if ($dataProvider['raeume'][0] != null) {
                $model_I = Immobilien::find()->where([">=", 'raeume', $dataProvider['raeume'][0]])->all();
            }
//4.Kondition:Sofern Suchrequestparameter enthält plz und Kohle
            if ($dataProvider['plz'][0] != null && $dataProvider['Kosten'][0] != null) {
                $model_I = Immobilien::find()->where(['l_plz_id' => $dataProvider['plz'][0]])->orWhere(["$operator", 'geldbetrag', $dataProvider['Kosten'][0]])->all();
            }
//5.Kondition:Sofern Suchrequestparameter enthält plz und Kohle
            if ($dataProvider['plz'][0] != null && $dataProvider['Kosten'][0] != null) {
                $model_I = Immobilien::find()->where(['l_plz_id' => $dataProvider['plz'][0]])->orWhere(['geldbetrag' => $dataProvider['Kosten'][0]])->all();
            }

            if (!empty($model_I)) {
                foreach ($model_I as $value) {
                    array_push($ArrayOfImmo, $value->id);
                }
            }
            var_dump($dataProvider);
            die();
            for ($i = 0; $i < count($ArrayOfImmo); $i++) {
                array_push($ArrayOfE, EDateianhang::findOne(['immobilien_id' => $ArrayOfImmo[$i]])->id);
            }
            for ($i = 0; $i < count($ArrayOfE); $i++) {
                if (preg_match($bmp, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfE[$i]])->dateiname) || preg_match($tif, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfE[$i]])->dateiname) || preg_match($png, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfE[$i]])->dateiname) || preg_match($psd, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfE[$i]])->dateiname) || preg_match($pcx, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfE[$i]])->dateiname) || preg_match($gif, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfE[$i]])->dateiname) || preg_match($jpeg, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfE[$i]])->dateiname) || preg_match($jpg, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfE[$i]])->dateiname) || preg_match($ico, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfE[$i]])->dateiname)) {
                    array_push($ArrayOfFilename, Dateianhang::findOne(['e_dateianhang_id' => $ArrayOfE[$i]])->dateiname);
                }
            }
            //$model_dateianhang = Dateianhang::find()->all();
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
//andernfalls übergebe keine Parameter
        } else {
            $dataProvider = $searchModel->search(NULL, NULL, NULL, NULL);
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
