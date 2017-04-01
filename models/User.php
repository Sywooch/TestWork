<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use yii\behaviors\TimestampBehavior;

class User extends ActiveRecord implements IdentityInterface
{
    /**
     * Определим переменную $users
     */
	static $users = [];

    /**
     * поведение для полей регистрации и изменения(редактирования)
     */
	    public function  behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord :: EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord :: EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ]
        ];

    }
	 /**
     * имя базы даннях с учетом возможного использования префикса в таблицах
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * правила полей
     */
    public function rules()
    {
      return [
            [['username'], 'required'],
            [['balance', 'created_at', 'updated_at'], 'integer'],
            [['username'], 'string', 'max' => 48],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/i', 'message' => 'Имя пользователя может содержать только буквы латинского алфавита и цифры'], 
            ];
    }

    /**
     * метки полей
     */
    public function attributeLabels()
    {
      return [
            'id' => 'ID пользователя',
            'username' => 'Логин',
            'auth_key' => 'Auth Key',
            'balance' => 'Баланс',
            'created_at' => 'Зарегистрирован',
            'updated_at' => 'Изменен',
        ];
    }

    /**
     * Генерируем рандомную строку при регистрации для контроля авторизации (надо бы лаконичней описать)
     */
    public function beforeSave($insert){
        if (parent::beforeSave($insert)){
            if ($this->isNewRecord){
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }
    
    
      static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
    
       static function findIdentity($id)
    {
        if (isset(static::$users[$id])){
            return static::$users[$id];

        } else {
            static::$users[$id] = static::findOne(['id'=>$id]);
            return  static::$users[$id];
        }
    }
    
    
      public function getId()
    {
        return $this->id;
    }

    /**
     * Пользователь по имени
     */
        static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
    
    
    public function getUserName()
    {
       return $this->username;
    }
    
       public function getAuthKey()
    {
        return $this->auth_key;
    }
    
      public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }
    
}