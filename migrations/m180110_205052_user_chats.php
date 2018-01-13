<?php

use yii\db\Migration;

/**
 * Class m180110_205052_user_chats
 */
class m180110_205052_user_chats extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_chats', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'chat_id' => $this->integer()->notNull(),
            'last_read' => $this->timestamp(),
        ]);

        $this->addForeignKey(
            'fk_user_chats_to_user',
            'user_chats',
            'user_id',
            'users',
            'id'
        );

        $this->addForeignKey(
            'fk_user_chats_to_chat',
            'user_chats',
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
            'fk_user_chats_to_user',
            'user_chats'
        );

        $this->dropForeignKey(
            'fk_user_chats_to_chat',
            'user_chats'
        );
        
        $this->dropTable('user_chats');
    }
}
