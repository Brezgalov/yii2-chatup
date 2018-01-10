<?php

use yii\db\Migration;

/**
 * Class m180110_203413_chats
 */
class m180110_203413_chats extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('chats', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('chats');
    }
}
