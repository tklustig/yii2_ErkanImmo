<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Kunde;

/**
 * backend\models\KundeSearch represents the model behind the search form about `frontend\models\Kunde`.
 */
 class KundeSearch extends Kunde
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'l_plz_id', 'bankverbindung_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['geschlecht', 'vorname', 'nachname', 'stadt', 'strasse', 'geburtsdatum', 'solvenz', 'angelegt_am', 'aktualisiert_am'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Kunde::find();

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
            'l_plz_id' => $this->l_plz_id,
            'geburtsdatum' => $this->geburtsdatum,
            'bankverbindung_id' => $this->bankverbindung_id,
            'angelegt_am' => $this->angelegt_am,
            'aktualisiert_am' => $this->aktualisiert_am,
            'angelegt_von' => $this->angelegt_von,
            'aktualisiert_von' => $this->aktualisiert_von,
        ]);

        $query->andFilterWhere(['like', 'geschlecht', $this->geschlecht])
            ->andFilterWhere(['like', 'vorname', $this->vorname])
            ->andFilterWhere(['like', 'nachname', $this->nachname])
            ->andFilterWhere(['like', 'stadt', $this->stadt])
            ->andFilterWhere(['like', 'strasse', $this->strasse])
            ->andFilterWhere(['like', 'solvenz', $this->solvenz]);

        return $dataProvider;
    }
}
