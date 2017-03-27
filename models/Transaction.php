<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%transaction}}".
 *
 * @property integer $id
 * @property integer $sender_id
 * @property integer $recipient_id
 * @property integer $sum
 * @property integer $created_at
 * @property integer $updated_at
 */
class Transaction extends \yii\db\ActiveRecord
{
	
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recipient_name','sum'], 'required'],
            [['sum'], 'integer', 'min' => 1,'max' => 99999999999],
            [['recipient_name'], 'string', 'max' => 48],
            ['recipient_name', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/i', 'message' => 'Имя пользователя может содержать только буквы латинского алфавита и цифры'],
            ['sum', 'match', 'pattern' => '/^[0-9]+$/i', 'message' => 'Сумма может содержать только цифры'],  
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID перевода',
            'sender_name' => 'Имя отправителя',
            'recipient_name' => 'Имя получателя',
            'sum' => 'Сумма перевода',
            'created_at' => 'Создана',
            'updated_at' => 'Изменена',
        ];
    }
}
