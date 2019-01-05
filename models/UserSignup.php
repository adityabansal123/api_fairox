<?php

namespace app\models;

use yii\base\Model;

class UserSignup extends Model{
    public $username;
    public $email;
    public $password;
    public $password_confirm;

    public function rules(){
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min'=>4, 'max'=>100],
            ['username', 'unique', 'targetClass'=>'app\models\User.php', 'message'=>'username already taken'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['username', 'string', 'max'=>200],
            ['email', 'unique', 'targetClass'=>'app\models\User.php', 'message'=>'email already taken'],

            ['password', 'string', 'min'=>6],
            ['password', 'required'],

            ['password_confirm', 'required'],
            ['password_confirm', 'compare', 'compareAttribute'=>'password', 'message'=>'passwords do not match']
        ];
    }

    public function signup(){
        if(!$this->validate()){
            return null;
        }
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->access_token = \Yii::$app->security->generateRandomString(32);
        $user->unique_key = time().mt_random(10,99);
        $user->setPassword($this->password);
        $user->generateAuthKey();
        return ($user->save()) ? $user : null;
    }
}