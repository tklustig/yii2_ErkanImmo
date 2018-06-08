<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Immobilien;

/**
 * backend\models\ImmobilienSearch represents the model behind the search form about `backend\models\Immobilien`.
 */
class ImmobilienSearch extends Immobilien {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'wohnflaeche', 'k_grundstuecksgroesse', 'raeume', 'l_plz_id', 'l_stadt_id', 'user_id', 'l_art_id', 'l_heizungsart_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['bezeichnung', 'strasse', 'balkon_vorhanden', 'fahrstuhl_vorhanden', 'sonstiges', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['geldbetrag', 'v_nebenkosten', 'k_provision'], 'number'],
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
    public function search($params, $id) {
        if ($id == 1) {
            $query = Immobilien::find()->where(['l_art_id' => 2]);

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
                'k_grundstuecksgroesse' => $this->k_grundstuecksgroesse,
                'raeume' => $this->raeume,
                'geldbetrag' => $this->geldbetrag,
                'k_provision' => $this->k_provision,
                'l_plz_id' => $this->l_plz_id,
                'l_stadt_id' => $this->l_stadt_id,
                'user_id' => $this->user_id,
                'l_art_id' => $this->l_art_id,
                'l_heizungsart_id' => $this->l_heizungsart_id,
                'angelegt_am' => $this->angelegt_am,
                'aktualisiert_am' => $this->aktualisiert_am,
                'angelegt_von' => $this->angelegt_von,
                'aktualisiert_von' => $this->aktualisiert_von,
            ]);

            $query->andFilterWhere(['like', 'bezeichnung', $this->bezeichnung])
                    ->andFilterWhere(['like', 'strasse', $this->strasse])
                    ->andFilterWhere(['like', 'balkon_vorhanden', $this->balkon_vorhanden])
                    ->andFilterWhere(['like', 'fahrstuhl_vorhanden', $this->fahrstuhl_vorhanden])
                    ->andFilterWhere(['like', 'sonstiges', $this->sonstiges]);
            return $dataProvider;
        } else if ($id == 2) {
            $query = Immobilien::find()->where(['l_art_id' => 1]);

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
                'v_nebenkosten' => $this->v_nebenkosten,
                'l_plz_id' => $this->l_plz_id,
                'l_stadt_id' => $this->l_stadt_id,
                'user_id' => $this->user_id,
                'l_art_id' => $this->l_art_id,
                'l_heizungsart_id' => $this->l_heizungsart_id,
                'angelegt_am' => $this->angelegt_am,
                'aktualisiert_am' => $this->aktualisiert_am,
                'angelegt_von' => $this->angelegt_von,
                'aktualisiert_von' => $this->aktualisiert_von,
            ]);
            $query->andFilterWhere(['like', 'bezeichnung', $this->bezeichnung])
                    ->andFilterWhere(['like', 'strasse', $this->strasse])
                    ->andFilterWhere(['like', 'balkon_vorhanden', $this->balkon_vorhanden])
                    ->andFilterWhere(['like', 'fahrstuhl_vorhanden', $this->fahrstuhl_vorhanden])
                    ->andFilterWhere(['like', 'sonstiges', $this->sonstiges]);
            return $dataProvider;
        }
    }

}
