<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Besichtigungstermin;

class TerminSearch extends Besichtigungstermin {

    public $foreignKeys;

    public function rules() {
        return [
            [['Relevanz', 'angelegt_von', 'aktualisiert_von', 'Immobilien_id'], 'integer'],
            [['uhrzeit', 'angelegt_am', 'aktualisiert_am'], 'safe'],
        ];
    }

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {

        $query = Besichtigungstermin::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        if ($this->foreignKeys != NULL) {
            $this->id = $this->foreignKeys;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'Relevanz' => $this->Relevanz,
            'uhrzeit' => $this->uhrzeit,
            'angelegt_am' => $this->angelegt_am,
            'aktualisiert_am' => $this->aktualisiert_am,
            'angelegt_von' => $this->angelegt_von,
            'aktualisiert_von' => $this->aktualisiert_von,
            'Immobilien_id' => $this->Immobilien_id,
        ]);
        /* $now = (new \DateTime('now'))->format('Y-m-d H:i:s');
          $query->andFilterWhere([
          '<=',
          $now,
          $this->uhrzeit
          ]); */
        return $dataProvider;
    }

}
