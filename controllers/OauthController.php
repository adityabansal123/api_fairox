<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\UserSignup;

class OauthController extends Controller{
    public function actionSignup(){
        $model = new UserSignup();
        if($model->load(\Yii::$app->getRequest()->getBodyParams(), '')){
            return $model->signup();
        }else{
            return 'Not Submitted';
        }
    }
}