<?php

namespace app\models;

use Yii;
use yii\base\Model;


/**
 *
 * Модель для формы регистрации/авторизации
 *
 */
class LoginForm extends Model
{
    public $username;
    public $rememberMe = true;
    private $_user = false;


    /**
     *  Правила для проверки полей
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'], 'string', 'max' => 48],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/i', 'message' => 'Имя пользователя может содержать только буквы латинского алфавита и цифры'],
            [['rememberMe'], 'boolean'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'rememberMe' => 'Запомнить меня на 30 дней'
        ];
    }


    public function login()
    {
        if ($this->validate()) {
            if (!$this->getUser()) {
                // регистрация
                $user = new User();
                $user->username = $this->username;
                $user->save();
                //Авторизация
                return Yii::$app->user->login($user,
                    $this->rememberMe ? 3600 * 24 * 30 : 0);
            }
            return Yii::$app->user->login(
                $this->getUser(),
                $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }


    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function isActive()
    {
        return $this->getUser()->active == 1 ? true : false;
    }
}
