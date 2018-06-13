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
            [['id', 'wohnflaeche', 'raeume', 'k_grundstuecksgroesse', 'l_plz_id', 'user_id', 'l_art_id', 'l_heizungsart_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['bezeichnung', 'sonstiges', 'strasse', 'balkon_vorhanden', 'fahrstuhl_vorhanden', 'stadt', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['geldbetrag', 'k_provision', 'v_nebenkosten'], 'number'],
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
    public function search($params, $id, $art) {
        if ($art == 1) {
            $query = Immobilien::find()->where(['id' => $id, 'l_art_id' => $art]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $this->load($params);
            return $dataProvider;
        } else if ($art == 2) {
            $query = Immobilien::find()->where(['l_art_id' => 1, 'id' => $id, 'l_art_id' => $art]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $this->load($params);

            if (!$this->validate()) {
                return $dataProvider;
            }
            return $dataProvider;
        }
    }

}
