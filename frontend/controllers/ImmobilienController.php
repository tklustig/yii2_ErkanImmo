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
use kartik\widgets\Alert;

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
        $modelImmobilien = Immobilien::find()->all();
        // Eruiere die jenigen Immobilien-Ids, die kein Bild haben
        foreach ($model_e as $id) {
            array_push($ArrayOfE, $id->immobilien_id);
        }
        foreach ($modelImmobilien as $id) {
            array_push($ArrayOfImmoAll, $id->id);
        }
        //verfrachte diese Id in ein Array
        $ArrayOfDifference = array_diff($ArrayOfImmoAll, $ArrayOfE);

        /* 	Eruiere alle Immobilien-Ids, die ein Bild haben und
          verfrachte den Dateinamen des jeweiligen Bildes... */
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
        //eruiere alle Attribute, die je nach Suchrequest angezeigt werden sollen und verfrachte sie in Arrays
        $ArrayOfArt = array();
        $ArrayOfMoney = array();
        $ArrayOfTown = array();
        $ArrayOfGroesse = array();
        $ArrayOfRooms = array();
        $ArrayOfPlz = array();
        $ArrayOfStreet = array();
        foreach ($modelImmobilien as $immoAttribute) {
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
        //sofern ein Suchrequest abgefeuert wurde,übergebe an das Searchmodel die Parameter...
        if ($searchPreview == 1) {
            /* 	Hier noch prüfen, ob dataProvider nur Null-Werte(der Request wurde zwar abgefeuert, 
              allerdings ohne Suchangaben) enthält. Wenn ja, dann 'zurück rendern' */
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, NULL, NULL, $searchPreview);
            if ($dataProvider['plz'][0] == null && $dataProvider['Kosten'][0] == null && $dataProvider['raeume'][0] == null) {
                $message = 'Ein Suchrequest abzufeuern ohne Suchparameter feszulegen erzeugt unnötigen Traffic. Bitte selektieren Sie Suchparameter!';
?><?=

                $this->Ausgabe($message, 'Hinweis', 2000, Growl::TYPE_CUSTOM);
                $dataProvider = $searchModel->search(NULL, NULL, NULL, NULL);
                return $this->render('_index', [
                            'count' => $count,
                            'ArrayOfFilename' => $ArrayOfFilename,
                            'ArrayOfImmo' => $ArrayOfImmo,
                            'ArrayOfDifference' => $ArrayOfDifference,
                            'model_immobilien' => $modelImmobilien,
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

            //sofern Suchangaben unvollständig
            if ($dataProvider['operator'][0] == null && $dataProvider['Kosten'][0] != null) {
                $message = 'Wenn Sie nach einem Kaufpreis/Miete suchen, müssen Sie entweder Höher als oder Weniger als auswählen.';
?><?=

                $this->Ausgabe($message, 'Hinweis', 2000, Growl::TYPE_WARNING, 'glyphicon glyphicon-flag');
                $dataProvider = $searchModel->search(NULL, NULL, NULL, NULL);
                return $this->render('_index', [
                            'count' => $count,
                            'ArrayOfFilename' => $ArrayOfFilename,
                            'ArrayOfImmo' => $ArrayOfImmo,
                            'ArrayOfDifference' => $ArrayOfDifference,
                            'model_immobilien' => $modelImmobilien,
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
            /* 	bei 2^3 Suchparameter muss es folglich 2^3-1 Konditionen geben. 
              1.Kondition:Sofern Suchrequestparameter enthält plz */
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
                $model_I = Immobilien::find()->where(['l_plz_id' => $dataProvider['plz'][0]])->andWhere(["$operator", 'geldbetrag', $dataProvider['Kosten'][0]])->all();
            }
            //5.Kondition:Sofern Suchrequestparameter enthält plz und Räume
            if ($dataProvider['plz'][0] != null && $dataProvider['raeume'][0] != null) {
                $model_I = Immobilien::find()->where(['l_plz_id' => $dataProvider['plz'][0]])->andWhere([">=", 'raeume', $dataProvider['raeume'][0]])->all();
            }
            //6.Kondition:Sofern Suchrequestparameter enthält Kohle und Räume
            if ($dataProvider['Kosten'][0] != null && $dataProvider['raeume'][0] != null) {
                $model_I = Immobilien::find()->where(["$operator", 'geldbetrag', $dataProvider['Kosten'][0]])->andWhere([">=", 'raeume', $dataProvider['raeume'][0]])->all();
            }
            //7.Kondition:Sofern Suchrequestparameter enthält Kohle und Räume und plz
            if ($dataProvider['Kosten'][0] != null && $dataProvider['raeume'][0] != null && $dataProvider['plz'][0] != null) {
                $model_I = Immobilien::find()->where(['l_plz_id' => $dataProvider['plz'][0]])->andWhere(["$operator", 'geldbetrag', $dataProvider['Kosten'][0]])->andWhere([">=", 'raeume', $dataProvider['raeume'][0]])->all();
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
                if (!empty($ArrayOfObjImmo))
                    $count = count($ArrayOfObjImmo);
            }
            if (empty($ArrayOfImmo)) {
                $count = 0;
?><?=

                Alert::widget([
                    'type' => Alert::TYPE_WARNING,
                    'title' => 'Hinweis',
                    'icon' => 'glyphicon glyphicon-exclamation-sign',
                    'body' => 'Ihr Suchrequest ergab keine Treffer. Versuchen sie es ggf. mit anderen Parameter erneut!',
                    'showSeparator' => true,
                    'delay' => false
                ]);
            }
            return $this->render('_index', [
                        'count' => $count,
                        'ArrayOfFilename' => $ArrayOfFilename,
                        'ArrayOfImmo' => $ArrayOfImmo,
                        'ArrayOfDifference' => $ArrayOfDifference,
                        'model_immobilien' => $modelImmobilien,
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
            //...andernfalls übergebe keine Parameter
        } else {
            $dataProvider = $searchModel->search(NULL, NULL, NULL, NULL);
            return $this->render('_index', [
                        'count' => $count,
                        'ArrayOfFilename' => $ArrayOfFilename,
                        'ArrayOfImmo' => $ArrayOfImmo,
                        'ArrayOfDifference' => $ArrayOfDifference,
                        'model_immobilien' => $modelImmobilien,
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
                    'art' => $art,
                    'id' => $id
        ]);
    }

    public function actionTermin($id) {
        return $this->redirect(['/termin/create', 'id' => $id]);
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

    //gekapselte Methoden
    protected function findModel($id) {
        if (($model = Immobilien::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    protected function Ausgabe($message, $typus = 'Warnung', $delay = 1000, $type = Growl::TYPE_GROWL, $art = 'glyphicon glyphicon-refresh') {
        return Growl::widget([
                    'type' => $type,
                    'title' => $typus,
                    'icon' => $art,
                    'body' => $message,
                    'showSeparator' => true,
                    'delay' => $delay,
                    'pluginOptions' => [
                        'showProgressbar' => true,
                        'placement' => [
                            'from' => 'top',
                            'align' => 'center',
                        ]
                    ]
        ]);
    }

}
