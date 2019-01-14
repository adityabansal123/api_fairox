<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    const ACTIVE_USER = 1;
    const INACTIVE_USER = 0;

    public static function tableName(){
        return '{{%user}}';
    }

    public function rules(){
        return [
          ['status','default','value'=>self::ACTIVE_USER],
          ['status', 'in', 'range'=>[self::ACTIVE_USER, self::INACTIVE_USER]]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
//        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
        return static::findOne(['id'=>$id, 'status'=>self::ACTIVE_USER]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        foreach (self::$users as $user) {
//            if ($user['accessToken'] === $token) {
//                return new static($user);
//            }
//        }
//
//        return null;
        if($user = static::findOne(['access_token'=>$token, 'status'=>self::ACTIVE_USER])){
            $expires = strtotime("+5 minute", strtotime($user->token_expires));
            if($expires > time()){
                $user->token_expires = date('Y-m-d H:i:s', strtotime('now'));
                $user->save();
                return $user;
            }else{
                $user->access_token = '';
                $user->save();
            }
        }
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
//        foreach (self::$users as $user) {
//            if (strcasecmp($user['username'], $username) === 0) {
//                return new static($user);
//            }
//        }
//
//        return null;
        return static::find()
                ->where(['username' => $username])
                ->orWhere(['email' => $username])
                ->andWhere(['status' => self::ACTIVE_USER])
                ->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function setPassword($password){
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey(){
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public function validatePasswordBasic($password, $userPassword)
    {
        return \Yii::$app->security->validatePassword($password, $userPassword);
    }
}
