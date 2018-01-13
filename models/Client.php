<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 *
 * @property UserChats[] $userChats
 * @property UserChats[] $userChats0
 * @property UserMessages[] $userMessages
 * @property UserMessages[] $userMessages0
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [
                [
                    'username', 
                    'email', 
                    'password', 
                    'auth_key', 
                    'access_token',
                    'state',
                    'status',
                ], 
                'string', 
                'max' => 255
            ],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserChats()
    {
        return $this->hasMany(UserChats::className(), ['chat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserMessages()
    {
        return $this->hasMany(UserMessages::className(), ['chat_id' => 'id']);
    }
}
