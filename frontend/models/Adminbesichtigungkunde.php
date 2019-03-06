<?php

namespace frontend\models;

use Yii;
use \frontend\models\base\Adminbesichtigungkunde as BaseAdminbesichtigungkunde;

class Adminbesichtigungkunde extends BaseAdminbesichtigungkunde {

    public function rules() {
        return array_replace_recursive(parent::rules(), [
            [['besichtigungstermin_id', 'admin_id', 'kunde_id'], 'required'],
            [['besichtigungstermin_id', 'admin_id', 'kunde_id'], 'integer']
        ]);
    }

}
