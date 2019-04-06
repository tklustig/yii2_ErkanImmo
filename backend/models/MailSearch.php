<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Mail;

class MailSearch extends Mail {

    public $choice_date;

    public function rules() {
        return [
            [['id', 'id_mailserver', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['mail_from', 'mail_to', 'mail_cc', 'mail_bcc', 'betreff', 'bodytext', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['choice_date'], 'boolean'],
        ];
    }

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = Mail::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_mailserver' => $this->id_mailserver,
            'angelegt_von' => $this->angelegt_von,
            'aktualisiert_von' => $this->aktualisiert_von,
        ]);
        if ($this->choice_date == 0) {
            $query->andFilterWhere(['<=', 'angelegt_am', $this->angelegt_am]);
            $query->andFilterWhere(['<=', 'aktualisiert_am', $this->aktualisiert_am]);
        } else {
            $query->andFilterWhere(['>=', 'angelegt_am', $this->angelegt_am]);
            $query->andFilterWhere(['>=', 'aktualisiert_am', $this->aktualisiert_am]);
        }

        $query->andFilterWhere(['like', 'mail_from', $this->mail_from])
                ->andFilterWhere(['like', 'mail_to', $this->mail_to])
                ->andFilterWhere(['like', 'mail_cc', $this->mail_cc])
                ->andFilterWhere(['like', 'mail_bcc', $this->mail_bcc])
                ->andFilterWhere(['like', 'betreff', $this->betreff])
                ->andFilterWhere(['like', 'bodytext', $this->bodytext]);
        return $dataProvider;
    }

}
