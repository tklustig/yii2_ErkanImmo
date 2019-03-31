<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Kopf;

class KopfSearch extends Kopf {

    public function rules() {
        return [
            [['id', 'user_id'], 'integer'],
            [['data'], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $query = Kopf::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
        ]);
        $query->andFilterWhere(['like', 'data', $this->data]);
        return $dataProvider;
    }

}
