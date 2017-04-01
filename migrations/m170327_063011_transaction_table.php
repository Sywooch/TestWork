<?php

use yii\db\Migration;

class m170327_063011_transaction_table extends Migration
{
   public function up()
    {
        /**
         * миграция для создания таблички с переводами
         */
        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey(11)->unsigned()->comment('ID перевода'),
            'sender_name' => $this->string(48)->notNull()->comment('Имя отправителя'),
            'recipient_name' => $this->string(48)->notNull()->comment('Имя получателя'),
            'sum'    => $this->integer(11)->notNull()->defaultValue(0)->comment('Сумма перевода'),
            'created_at' => $this->integer(11)->notNull()->unsigned()->comment('Создана'),
            'updated_at' => $this->integer(11)->notNull()->unsigned()->comment('Изменена'),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%transaction}}');
    }
}
