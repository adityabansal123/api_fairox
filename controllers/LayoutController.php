<?php

namespace app\controllers;

use app\models\Layout;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\Response;

class LayoutController extends ActiveController{
    public function behaviors(){
        return [
            [
                'class' => ContentNegotiator::className(),
                'only' => ['index', 'view'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public $modelClass = 'app\models\Layout';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'data',
    ];

    //Create
    public function actionPost(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $layout = new Layout();
        $layout->attributes = \yii::$app->request->post();
        if($layout->validate()){
            $layout->save();
            return [
                'status' => true,
                'data' => 'Record Successfully Created'
            ];
        }else{
            return [
                'status' => false,
                'data' => $layout->getErrors()
            ];
        }
    }

    public function actionGet(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $layout = Layout::find()->all();

        if(count($layout) > 0){
            return [
                'status' => true,
                'data' => $layout
            ];
        }else{
            return [
                'status' => false,
                'data' => 'No data found'
            ];
        }
    }

    public function actionPut(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \Yii::$app->request->post();
        $layout = Layout::find()
                    ->where([
                        'ID' => $attributes['id']
                    ])
                    ->one();
        if(count($layout) > 0){
            $layout->attributes = \yii::$app->request->post();
            $layout->save();
            return [
                'status' => true,
                'data' => 'Layout updated successfully'
            ];
        }else{
            return [
                'status' => false,
                'data' => 'No record found'
            ];
        }
    }

    public function actionDeleteRoll(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attributes = \Yii::$app->request->post();
        $layout = Layout::find()
                ->where([
                    'ID' => $attributes['id']
                ])
                ->one();
        if(count($layout) > 0){
            $layout->delete();
            return [
                'status' => true,
                'data' => 'Layout deleted successfully'
            ];
        }else{
            return [
                'status' => false,
                'data' => 'No record found'
            ];
        }
    }
}