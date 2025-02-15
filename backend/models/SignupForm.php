<?php

namespace backend\models;

use yii\base\Model;
use common\models\User;
use kartik\password\StrengthValidator;

class SignupForm extends Model {

    public $username;
    public $email;
    public $password;
    public $telefon;

    public function rules() {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ['telefon', 'trim'],
            ['telefon', 'required'],
            ['telefon', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This number has already been taken'],
            ['telefon', 'string', 'min' => 5, 'max' => 255],
            [['password'], 'required'],
            [['password'], StrengthValidator::className(), 'min' => 8, 'digit' => 1, 'special' => 1, 'lower' => 1, 'upper' => 1]
        ];
    }

    public function signup() {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->telefon = $this->telefon;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }

}
