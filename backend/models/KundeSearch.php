<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Kunde;
use frontend\models\LPlz;

class KundeSearch extends Kunde {

    public $choice_date;

    public function rules() {
        return [
            [['id', 'l_plz_id', 'bankverbindung_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['geschlecht', 'vorname', 'nachname', 'stadt', 'strasse', 'geburtsdatum', 'solvenz', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['choice_date'], 'boolean']
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $query = Kunde::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        if (!empty($this->l_plz_id) && !empty(LPlz::findOne(['plz' => $this->l_plz_id])->id)) {
            $id = LPlz::findOne(['plz' => $this->l_plz_id])->id;
            $query->andFilterWhere([
                'l_plz_id' => $id,
            ]);
        }
        if (!empty($this->l_plz_id) && empty(LPlz::findOne(['plz' => $this->l_plz_id])->id)) {
            $id = 0;
            $query->andFilterWhere([
                'l_plz_id' => $id,
            ]);
        }
        if (!empty($this->geschlecht) && !empty(LGeschlecht::findOne(['typus' => $this->geschlecht])->id)) {
            $id = LGeschlecht::findOne(['typus' => $this->geschlecht])->id;
            $query->andFilterWhere([
                'geschlecht' => $id,
            ]);
        }
        if (!empty($this->geschlecht) && empty(LGeschlecht::findOne(['typus' => $this->geschlecht])->id)) {
            $id = 0;
            $query->andFilterWhere([
                'geschlecht' => $id,
            ]);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'angelegt_von' => $this->angelegt_von,
            'aktualisiert_von' => $this->aktualisiert_von,
            'solvenz' => $this->solvenz,
        ]);
        $query->andFilterWhere(['like', 'vorname', $this->vorname])
                ->andFilterWhere(['like', 'nachname', $this->nachname])
                ->andFilterWhere(['like', 'stadt', $this->stadt])
                ->andFilterWhere(['like', 'strasse', $this->strasse]);

        if ($this->choice_date == 0) {
            $query->andFilterWhere(['<=', 'angelegt_am', $this->angelegt_am]);
            $query->andFilterWhere(['<=', 'aktualisiert_am', $this->aktualisiert_am]);
            $query->andFilterWhere(['<=', 'geburtsdatum', $this->geburtsdatum]);
        } else if ($this->choice_date == 1) {
            $query->andFilterWhere(['>=', 'angelegt_am', $this->angelegt_am]);
            $query->andFilterWhere(['>=', 'aktualisiert_am', $this->aktualisiert_am]);
            $query->andFilterWhere(['>=', 'geburtsdatum', $this->geburtsdatum]);
        }
        return $dataProvider;
    }

}
