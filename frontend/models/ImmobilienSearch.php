<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Immobilien;
use kartik\widgets\Growl;

class ImmobilienSearch extends Immobilien {

    public $choice_date;

    public function rules() {
        return [
            [['id', 'wohnflaeche', 'raeume', 'k_grundstuecksgroesse', 'l_plz_id', 'user_id', 'l_art_id', 'l_heizungsart_id', 'angelegt_von', 'aktualisiert_von'], 'integer'],
            [['bezeichnung', 'sonstiges', 'strasse', 'balkon_vorhanden', 'fahrstuhl_vorhanden', 'stadt', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['geldbetrag', 'k_provision', 'v_nebenkosten'], 'number'],
            [['choice_date'], 'number'],
        ];
    }

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, $id = NULL, $art = NULL, $searchPreview = NULL) {
        if ($searchPreview == 1) {
            $CompareMoney = $params['ImmobilienSearch']['choice_date'];
            $money = $params['ImmobilienSearch']['geldbetrag'];
            $ArrayOfArguments = array();
            $IdPlz = $params['ImmobilienSearch']['l_plz_id'];
            if (empty($IdPlz))
                $IdPlz = null;
            $money = $params['ImmobilienSearch']['geldbetrag'];
            if (empty($money))
                $money = null;
            $raeume = $params['ImmobilienSearch']['raeume'];
            if (empty($raeume))
                $raeume = null;
            if (!empty($CompareMoney) && $CompareMoney == 1)
                $operator = '>';
            else if (!empty($CompareMoney) && $CompareMoney == 2)
                $operator = '<';
            else
                $operator = null;

            $ArrayOfArguments['plz'][0] = $IdPlz;
            $ArrayOfArguments['operator'][0] = $operator;
            $ArrayOfArguments['Kosten'][0] = $money;
            $ArrayOfArguments['raeume'][0] = $raeume;
            return $ArrayOfArguments;
        }

        if ($art == 1) {
            $query = Immobilien::find()->where(['l_art_id' => 1, 'id' => $id]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $this->load($params);
            $query->andFilterWhere(['=', 'fahrstuhl_vorhanden', $this->fahrstuhl_vorhanden]);
            $query->andFilterWhere(['=', 'balkon_vorhanden', $this->balkon_vorhanden]);
            return $dataProvider;
        } else if ($art == 2) {
            $query = Immobilien::find()->where(['l_art_id' => 2, 'id' => $id]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $this->load($params);
            $query->andFilterWhere(['=', 'fahrstuhl_vorhanden', $this->fahrstuhl_vorhanden]);
            $query->andFilterWhere(['=', 'balkon_vorhanden', $this->balkon_vorhanden]);
            return $dataProvider;
        }
    }

}
