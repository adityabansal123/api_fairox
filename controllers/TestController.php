<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Response;


class TestController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
            $behaviors = parent::behaviors();
            $behaviors['authenticator'] = [
//                'class' => HttpBasicAuth::className(),
//                'auth' => function($username, $password){
//                    $user = User::findByUsername($username);
//                    if($user && $user->validatePasswordBasic($password, $user->password)){
//                        return $user;
//                    }
//                }
                'class' => HttpBearerAuth::className()

            ];
            $behaviors['verbs'] = [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'index' => ['POST']
                ]
            ];
            return $behaviors;
    }

    public function actionIndex(){
        return 'hello';
//        public $modelClass = 'app\models\Locations';
//        public $serializer = [
//            'class' => 'yii\rest\Serializer',
//            'collectionEnvelope' => 'items',
//        ];
    }


}
