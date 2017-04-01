<?php

use yii\db\Migration;

class m170326_202211_user_table extends Migration
{
    public function up()
    {
        /**
         * миграция создания таблички для пользователя
         */
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(11)->unsigned()->comment('ID пользователя'),
            'username' => $this->string(48)->notNull()->unique()->comment('Никнейм'),
            'auth_key' => $this->string(32),
            'balance'    => $this->integer(11)->notNull()->defaultValue(0)->comment('Баланс'),
            'created_at' => $this->integer(11)->notNull()->unsigned()->comment('Зарегистрирован'),
            'updated_at' => $this->integer(11)->notNull()->unsigned()->comment('Изменен'),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
    
}
