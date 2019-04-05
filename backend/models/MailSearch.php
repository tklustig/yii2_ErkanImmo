<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Mail;

/**
 * app\models\MailSearch represents the model behind the search form about `backend\models\Mail`.
 */
 class MailSearch extends Mail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_mailserver', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['mail_from', 'mail_to', 'mail_cc', 'mail_bcc', 'betreff', 'bodytext', 'angelegt_am', 'aktualisiert_am'], 'safe'],
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
        $query = Mail::find();

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
            'id_mailserver' => $this->id_mailserver,
            'angelegt_am' => $this->angelegt_am,
            'angelegt_von' => $this->angelegt_von,
            'aktualisiert_am' => $this->aktualisiert_am,
            'aktualisiert_von' => $this->aktualisiert_von,
        ]);

        $query->andFilterWhere(['like', 'mail_from', $this->mail_from])
            ->andFilterWhere(['like', 'mail_to', $this->mail_to])
            ->andFilterWhere(['like', 'mail_cc', $this->mail_cc])
            ->andFilterWhere(['like', 'mail_bcc', $this->mail_bcc])
            ->andFilterWhere(['like', 'betreff', $this->betreff])
            ->andFilterWhere(['like', 'bodytext', $this->bodytext]);

        return $dataProvider;
    }
}
