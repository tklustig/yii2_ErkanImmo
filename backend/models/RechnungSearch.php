<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Rechnung;

/**
 * backend\models\RechnungSearch represents the model behind the search form about `backend\models\Rechnung`.
 */
 class RechnungSearch extends Rechnung
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mwst_id', 'kunde_id', 'makler_id', 'kopf_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['datumerstellung', 'datumfaellig', 'beschreibung', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['geldbetrag'], 'number'],
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
        $query = Rechnung::find();

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
            'datumerstellung' => $this->datumerstellung,
            'datumfaellig' => $this->datumfaellig,
            'geldbetrag' => $this->geldbetrag,
            'mwst_id' => $this->mwst_id,
            'kunde_id' => $this->kunde_id,
            'makler_id' => $this->makler_id,
            'kopf_id' => $this->kopf_id,
            'angelegt_von' => $this->angelegt_von,
            'aktualisiert_von' => $this->aktualisiert_von,
            'angelegt_am' => $this->angelegt_am,
            'aktualisiert_am' => $this->aktualisiert_am,
        ]);

        $query->andFilterWhere(['like', 'beschreibung', $this->beschreibung]);

        return $dataProvider;
    }
}
