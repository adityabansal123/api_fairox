<?php

namespace app\controllers;

use app\models\User;
use yii\web\Controller;
use yii\filters\auth\HttpBasicAuth;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
//    public function behaviors()
//    {
//            $behaviors = parent::behaviors();
//            $behaviors['authenticator'] = [
//                'class' => HttpBasicAuth::className(),
//                'auth' => function($username, $password){
//                    $user = User::findByUsername($username);
//                    if($user && $user->validatePassword($password, $user->password)){
//                        return $user;
//                    }
//                }
//            ];
//            return $behaviors;
//    }

    public function actionIndex(){
        return 'hello';
    }


}
