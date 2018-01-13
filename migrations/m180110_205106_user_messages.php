<?php

use yii\db\Migration;

/**
 * Class m180110_205106_user_messages
 */
class m180110_205106_user_messages extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_messages', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'chat_id' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'date' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_user_messages_to_user',
            'user_messages',
            'user_id',
            'users',
            'id'
        );

        $this->addForeignKey(
            'fk_user_messages_to_chat',
            'user_messages',
            'chat_id',
            'chats',
            'id'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk_user_messages_to_user',
            'user_messages'
        );

        $this->dropForeignKey(
            'fk_user_messages_to_chat',
            'user_messages'
        );

        $this->dropTable('user_messages');
    }
}
