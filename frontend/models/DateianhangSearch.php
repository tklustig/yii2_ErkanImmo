<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Dateianhang;

/**
 * frontend\models\DateianhangSearch represents the model behind the search form about `frontend\models\Dateianhang`.
 */
class DateianhangSearch extends Dateianhang {

    public function rules() {
        return [
            [['id'], 'integer'],
            [['bezeichnung', 'dateiname'], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $query = Dateianhang::find()->where(['bezeichnung' => 'Bilder für das Frontend']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        return $dataProvider;
    }

}
