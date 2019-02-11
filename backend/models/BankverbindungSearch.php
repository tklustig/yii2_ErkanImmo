<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Bankverbindung;

/**
 * backend\models\BankverbindungSearch represents the model behind the search form about `backend\models\Bankverbindung`.
 */
 class BankverbindungSearch extends Bankverbindung
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['art', 'iban', 'bic', 'angelegt_am', 'aktualisiert_am'], 'safe'],
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
        $query = Bankverbindung::find();

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
            'angelegt_am' => $this->angelegt_am,
            'aktualisiert_am' => $this->aktualisiert_am,
            'angelegt_von' => $this->angelegt_von,
            'aktualisiert_von' => $this->aktualisiert_von,
        ]);

        $query->andFilterWhere(['like', 'art', $this->art])
            ->andFilterWhere(['like', 'iban', $this->iban])
            ->andFilterWhere(['like', 'bic', $this->bic]);

        return $dataProvider;
    }
}
