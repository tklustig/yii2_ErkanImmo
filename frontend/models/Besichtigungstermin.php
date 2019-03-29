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
            [['uhrzeit', 'Immobilien_id'], 'required'],
            [['uhrzeit', 'angelegt_am', 'aktualisiert_am'], 'safe'],
            [['angelegt_von', 'aktualisiert_von', 'Immobilien_id'], 'integer'],
            [['Relevanz'], 'string', 'max' => 1]
        ]);
    }
	
}
