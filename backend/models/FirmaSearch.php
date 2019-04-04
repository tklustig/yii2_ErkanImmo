<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Firma;

/**
 * app\models\FirmaSearch represents the model behind the search form about `backend\models\Firma`.
 */
 class FirmaSearch extends Firma
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'l_rechtsform_id', 'hausnummer', 'l_plz_id', 'anzahlMitarbeiter', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['firmenname', 'strasse', 'ort', 'geschaeftsfuehrer', 'prokurist', 'umsatzsteuerID', 'angelegt_am', 'aktualisiert_am'], 'safe'],
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
        $query = Firma::find();

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
            'l_rechtsform_id' => $this->l_rechtsform_id,
            'hausnummer' => $this->hausnummer,
            'l_plz_id' => $this->l_plz_id,
            'anzahlMitarbeiter' => $this->anzahlMitarbeiter,
            'angelegt_von' => $this->angelegt_von,
            'aktualisiert_von' => $this->aktualisiert_von,
            'angelegt_am' => $this->angelegt_am,
            'aktualisiert_am' => $this->aktualisiert_am,
        ]);

        $query->andFilterWhere(['like', 'firmenname', $this->firmenname])
            ->andFilterWhere(['like', 'strasse', $this->strasse])
            ->andFilterWhere(['like', 'ort', $this->ort])
            ->andFilterWhere(['like', 'geschaeftsfuehrer', $this->geschaeftsfuehrer])
            ->andFilterWhere(['like', 'prokurist', $this->prokurist])
            ->andFilterWhere(['like', 'umsatzsteuerID', $this->umsatzsteuerID]);

        return $dataProvider;
    }
}
