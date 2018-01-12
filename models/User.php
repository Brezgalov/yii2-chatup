<?php

namespace app\models;

use app\models\UserChats;
use \yii\db\Query;
use Yii;
use yii\helpers\Html;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $auth_key;
    public $access_token;

    public static function findBy($key, $value) {
        return (new Query())
            ->select(['*'])
            ->from('users')
            ->where([$key => $value])
            ->all();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $found = self::findBy('id', $id);
        $user = array_shift($found);
        return (!empty($user))? new static($user) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $found = self::findBy('access_token', $token);
        $user = array_shift($found);
        return (!empty($user))? new static($user) : null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $found = self::findBy('username', $username);
        $user = array_shift($found);
        return (!empty($user))? new static($user) : null;
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        $found = self::findBy('email', $email);
        $user = array_shift($found);
        return (!empty($user))? new static($user) : null;
    }

    /**
     * Returns users list
     */
    public static function all() 
    {
        return (new Query())
            ->select(['*'])
            ->from('users')
            ->all();
    }

    /**
     * Creates new user
     */
    public static function create($email, $username, $password, $args = []) 
    {
        $command = Yii::$app->db->createCommand();
        $args = array_merge(
            $args, 
            [
                'email' => $email,
                'username' => $username,
                'password' => $password
            ]
        );
        $res = $command->insert('users', $args)->execute();
        if ($res) {
            $user = User::findByEmail($email);
            Yii::$app->user->login($user, 0);
        }
        return $res;
    }

    public function getChats() {
        return UserChats::find()
            ->with('chat')
            ->where([
                'user_id' => $this->id
            ])->all();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return password_verify(
            $password,
            $this->password
        );
    }
}
