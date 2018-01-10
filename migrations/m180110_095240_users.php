<?php

use yii\db\Migration;

/**
 * Class m180110_095240_users
 */
class m180110_095240_users extends Migration
{
    /**
     * @inheritdoc
     */
    public function up() {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'auth_key' => $this->string(),
            'access_token' => $this->string(),
        ]);
        //crate index for username and email
        $this->createIndex(
            'username-index',
            'users',
            'username',
            true
        );
        $this->createIndex(
            'email-index',
            'users',
            'email',
            true
        );

        $this->batchInsert(
            'users', 
            ['username', 'email', 'password', 'auth_key', 'access_token'], 
            [
                [
                    'admin', 
                    'admin@gmail.com', 
                    password_hash('admin', PASSWORD_BCRYPT), 
                    password_hash('adminToken', PASSWORD_BCRYPT),
                    password_hash('adminToken', PASSWORD_BCRYPT),
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropIndex(
            'username-index',
            'users'
        );
        $this->dropIndex(
            'email-index',
            'users'
        );
        $this->dropTable('users');
    }
}
