<?php

use yii\db\Migration;

/**
 * Class m180113_085410_session
 */
class m180113_085410_session extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('session', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'addr' => $this->string(),
            'expire' => $this->integer(),
            'last_write' => $this->integer(),
            'data' => $this->binary(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('session');
    }
}
