<?php

namespace backend\controllers;

use Yii;
use frontend\models\LPlz;
use yii\web\Controller;
use yii\helpers\Json;

class PlzController extends Controller {

    public function actionGetCityProvince($zipId) {
        $location = LPlz::findOne($zipId);
        return Json::encode($location);
    }

}
