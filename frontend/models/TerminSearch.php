<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Besichtigungstermin;

/**
 * TerminSearch represents the model behind the search form of `frontend\models\Besichtigungstermin`.
 */
class TerminSearch extends Besichtigungstermin
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'Relevanz', 'angelegt_von', 'aktualisiert_von', 'Immobilien_id'], 'integer'],
            [['uhrzeit', 'angelegt_am', 'aktualisiert_am'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Besichtigungstermin::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'uhrzeit' => $this->uhrzeit,
            'Relevanz' => $this->Relevanz,
            'angelegt_am' => $this->angelegt_am,
            'aktualisiert_am' => $this->aktualisiert_am,
            'angelegt_von' => $this->angelegt_von,
            'aktualisiert_von' => $this->aktualisiert_von,
            'Immobilien_id' => $this->Immobilien_id,
        ]);

        return $dataProvider;
    }
}
