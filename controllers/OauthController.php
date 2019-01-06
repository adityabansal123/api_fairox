<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\UserSignup;
use app\models\LoginForm;
use app\models\User;

class OauthController extends Controller{
    public function actionSignup(){
        $model = new UserSignup();
        if($model->load(\Yii::$app->getRequest()->getBodyParams(), '') && $model->signup()){
            return $model->signup();
        }else{
            return 'Not Submitted';
        }
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