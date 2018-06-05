<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\Adminbesichtigungkunde as BaseAdminbesichtigungkunde;

/**
 * This is the model class for table "adminbesichtigungkunde".
 */
class Adminbesichtigungkunde extends BaseAdminbesichtigungkunde
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['besichtigungstermin_id', 'admin_id', 'kunde_id'], 'required'],
            [['besichtigungstermin_id', 'admin_id', 'kunde_id'], 'integer']
        ]);
    }
	
}
