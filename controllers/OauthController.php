<?php

namespace app\controllers;

use app\controllers\ApiBaseController;
use app\models\UserSignup;
use app\models\LoginForm;
use app\models\User;

class OauthController extends ApiBaseController{
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
            return $this->response(200, $user->save());
        }
        return $this->response(201, json_encode($model), false);
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
            return $this->response(200, $user->access_token);
        }
        return $this->response(201, json_encode($model), false);
    }
}