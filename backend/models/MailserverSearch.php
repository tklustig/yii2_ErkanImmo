<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Mailserver;

class MailserverSearch extends Mailserver {

    public function rules() {
        return [
            [['id', 'port', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['serverURL', 'serverHost', 'username', 'password', 'useEncryption', 'encryption', 'angelegt_am', 'aktualisiert_am'], 'safe'],
        ];
    }

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = Mailserver::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'port' => $this->port,
            'angelegt_von' => $this->angelegt_von,
            'aktualisiert_von' => $this->aktualisiert_von,
            'angelegt_am' => $this->angelegt_am,
            'aktualisiert_am' => $this->aktualisiert_am,
        ]);

        $query->andFilterWhere(['like', 'serverURL', $this->serverURL])
                ->andFilterWhere(['like', 'serverHost', $this->serverHost])
                ->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'password', $this->password])
                ->andFilterWhere(['like', 'useEncryption', $this->useEncryption])
                ->andFilterWhere(['like', 'encryption', $this->encryption]);

        return $dataProvider;
    }

}
