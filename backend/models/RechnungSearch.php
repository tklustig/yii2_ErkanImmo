<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Rechnung;

class RechnungSearch extends Rechnung {

    public $choice_date;

    public function rules() {
        return [
            [['id', 'mwst_id', 'kunde_id', 'makler_id', 'kopf_id', 'rechungsart_id', 'aktualisiert_von', 'angelegt_von'], 'integer'],
            [['datumerstellung', 'datumfaellig', 'beschreibung', 'vorlage', 'rechnungPlain', 'aktualisiert_am', 'angelegt_am'], 'safe'],
            [['geldbetrag'], 'number'],
            [['choice_date'], 'boolean'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $query = Rechnung::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate())
            return $dataProvider;
        if ($this->choice_date == 0) {
            $query->andFilterWhere(['<=', 'datumerstellung', $this->datumerstellung]);
            $query->andFilterWhere(['<=', 'datumfaellig', $this->datumfaellig]);
            $query->andFilterWhere(['<=', 'angelegt_am', $this->angelegt_am]);
        } else {
            $query->andFilterWhere(['>=', 'datumerstellung', $this->datumerstellung]);
            $query->andFilterWhere(['>=', 'datumfaellig', $this->datumfaellig]);
            $query->andFilterWhere(['>=', 'angelegt_am', $this->angelegt_am]);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'geldbetrag' => $this->geldbetrag,
            'mwst_id' => $this->mwst_id,
            'kunde_id' => $this->kunde_id,
            'makler_id' => $this->makler_id,
            'kopf_id' => $this->kopf_id,
            'rechungsart_id' => $this->rechungsart_id,
            'aktualisiert_von' => $this->aktualisiert_von,
            'angelegt_von' => $this->angelegt_von,
            'aktualisiert_am' => $this->aktualisiert_am,
            'angelegt_am' => $this->angelegt_am,
        ]);

        $query->andFilterWhere(['like', 'beschreibung', $this->beschreibung])
                ->andFilterWhere(['like', 'vorlage', $this->vorlage])
                ->andFilterWhere(['like', 'rechnungPlain', $this->rechnungPlain]);

        return $dataProvider;
    }

}
