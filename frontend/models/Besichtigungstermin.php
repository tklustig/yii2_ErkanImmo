<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\Besichtigungstermin as BaseBesichtigungstermin;

/**
 * This is the model class for table "besichtigungstermin".
 */
class Besichtigungstermin extends BaseBesichtigungstermin
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['l_plz_id', 'strasse', 'uhrzeit', 'l_stadt_id', 'Immobilien_id'], 'required'],
            [['l_plz_id', 'l_stadt_id', 'angelegt_von', 'aktualisiert_von', 'Immobilien_id'], 'integer'],
            [['uhrzeit', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['strasse'], 'string', 'max' => 64],
            [['Relevanz'], 'string', 'max' => 1]
        ]);
    }
	
}
