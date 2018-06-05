<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Immobilien;

/**
 * frontend\models\ImmobilienSearch represents the model behind the search form about `frontend\models\Immobilien`.
 */
class ImmobilienSearch extends Immobilien {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'wohnflaeche', 'raeume', 'l_plz_id', 'l_stadt_id', 'user_id', 'l_art_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['bezeichnung', 'strasse', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['geldbetrag'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Immobilien::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'wohnflaeche' => $this->wohnflaeche,
            'raeume' => $this->raeume,
            'geldbetrag' => $this->geldbetrag,
            'l_plz_id' => $this->l_plz_id,
            'l_stadt_id' => $this->l_stadt_id,
            'user_id' => $this->user_id,
            'l_art_id' => $this->l_art_id,
            'angelegt_am' => $this->angelegt_am,
            'aktualisiert_am' => $this->aktualisiert_am,
            'angelegt_von' => $this->angelegt_von,
            'aktualisiert_von' => $this->aktualisiert_von,
        ]);

        $query->andFilterWhere(['like', 'bezeichnung', $this->bezeichnung])
                ->andFilterWhere(['like', 'strasse', $this->strasse]);

        return $dataProvider;
    }

}
