<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Transaction;
use app\models\User;
use yii\data\ActiveDataProvider;


class SiteController extends Controller
{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    /*
        *
        * Экшн главной/профиля
        *
    */

    public function actionIndex()
    {
        // Если пользователь зашел как гость, грузим представление для гостя "index",
        //в противном случае грузим представление профиля "index-user"

        if (Yii::$app->user->isGuest) {
            return $this->render('index');
        } else {
            // Получаем объект пользователя
            $user = User::find()->where(['username' => Yii::$app->user->identity->username])->asArray()->One();
            // Собираем все переводы с участием пользователя
            $transaction = new ActiveDataProvider([
                'query' => Transaction::find()
                    ->where(['sender_name' => $user['username']])
                    ->orWhere(['recipient_name' => $user['username']])
                    ->asArray(),
            ]);
            // Передаем данные в представление профиля
            return $this->render('index-user', [
                'user' => $user,
                'transaction' => $transaction,
            ]);
        }
    }

    /*
       *
       * Экшн осуществления переводов "попугаев" медлу пользователями.
       *
   */
    public function actionTransaction()
    {
        //получаем новый объект перевода
        $model = new Transaction();


        // если форма пришла, грузим в модель
        if ($model->load(Yii::$app->request->post())) {

            //Костыль, запрещающий переводы самому себе, с переходом в профиль
            if (Yii::$app->user->identity->username == $model->recipient_name) {
                Yii::$app->session->setFlash('error', "Ошибка перевода"
                    . "<br>Самому себе переводить средства бессмысленно");
                return $this->goHome();
            }

            //Получаем объект отправителя
            $sender = User::findOne(['username' => Yii::$app->user->identity->username]);

            // Проверяем, есть ли получатель в базе
            if ($recipient = User::findOne(['username' => $model->recipient_name])) {
                $recipient->balance = $recipient->balance + $model->sum;
            } else {
                // Нет получателя, регистрируем его
                $recipient = new User();
                $recipient->username = $model->recipient_name;
                $recipient->balance = $model->sum;
            }
            // Сохраняем/Обновляем получателя с новым балансом
            $recipient->save();

            // Вычитаем с баланса отправителя расходную сумму
            $sender->balance = $sender->balance - $model->sum;
            $sender->save();

            // Записываем отправителя в объект перевода, Имя получателя пришло постом
            $model->sender_name = $sender->username;
            $model->save();

            Yii::$app->session->setFlash('success', 'Перевод успешно проведен <br>Вы успешно перевели <b>' . $model->sum . '</b> пользователю <b>' . $model->recipient_name . '</b>');
            // переходим на главную/профиль
            return $this->goHome();
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /*
        *
        * Экшн входа/регистрации с последующим переходом на главную(профиль)
        *
    */
    public function actionLogin()
    {
        // Если пользователь авторизован и как-то сюда зашел, направляем его на главную/профиль
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        // Создаем объект модели для формы входа
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //Авторизовались и пошли в профиль
            return $this->goHome();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /*
        *
        * Экшн выхода и редирект на главную
        *
    */

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


}
