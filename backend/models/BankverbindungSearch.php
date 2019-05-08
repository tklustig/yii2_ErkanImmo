<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Bankverbindung;

class BankverbindungSearch extends Bankverbindung {

    public function rules() {
        return [
            [['id', 'blz', 'kontoNr', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['laenderkennung', 'institut', 'iban', 'bic', 'angelegt_am', 'aktualisiert_am'], 'safe'],
        ];
    }

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        if (!empty($params['BankverbindungSearch']['kunde_id']))
            $query = Bankverbindung::find()->where(['kunde_id' => $params['BankverbindungSearch']['kunde_id']]);
        else
            $query = Bankverbindung::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'blz' => $this->blz,
            'kontoNr' => $this->kontoNr,
            'angelegt_am' => $this->angelegt_am,
            'aktualisiert_am' => $this->aktualisiert_am,
            'angelegt_von' => $this->angelegt_von,
            'aktualisiert_von' => $this->aktualisiert_von,
        ]);

        $query->andFilterWhere(['like', 'laenderkennung', $this->laenderkennung])
                ->andFilterWhere(['like', 'institut', $this->institut])
                ->andFilterWhere(['like', 'iban', $this->iban])
                ->andFilterWhere(['like', 'bic', $this->bic]);

        return $dataProvider;
    }

}
