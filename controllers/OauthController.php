<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\UserSignup;
use app\models\LoginForm;
use app\models\User;

class OauthController extends Controller{
    public function actionSignup(){
        $model = new UserSignup();
        if($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->validate()){
            $user = new User();
            $user->username = $model->username;
            $user->email = $model->email;
            $user->access_token = \Yii::$app->security->generateRandomString(32);
            $user->unique_key = time().mt_rand(10,99);
            $user->created_at = date('Y-m-d H:i:s', strtotime('now'));
            $user->setPassword($model->password);
            $user->generateAuthKey();
            return ($user->save()) ? $user : 'Failed to save';
        }
        return $model;
    }
    public function actionLogin(){
        $model = new LoginForm();
        if($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->login()){
            $user = User::findOne([
                'username'=>$model->username
            ]);
            $user->access_token = \Yii::$app->security->generateRandomString();
            $user->token_expires = date('Y-m-d H:i:s', time());
            $user->save();

            return [
                'token' => $user->access_token,
                'expiry' => $user->token_expires
            ];
        }
    }
}