<?php

namespace app\models;

use Yii;
use app\models\Messages;
use app\models\UserChats;

/**
 * This is the model class for table "chats".
 *
 * @property int $id
 * @property string $name
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    public function getMessages() {
        return Messages::find()
            ->with('author')
            ->where(['chat_id' => $this->id])
            ->orderBy('date ASC')
            ->all();
    }

    public function getUsers() {
        $result = [];
        $userChats = UserChats::find()->where(['chat_id' => $this->id])->all();
        foreach ($userChats as $userChat) {
            $user = $userChat->getUser()->one();
            array_push($result, $user);
        }
        return $result;
    }

    public function hasUser($id) {
        return (bool) UserChats::find()
            ->where(['chat_id' => $this->id])
            ->where(['user_id' => $id])
            ->one();
    }
}
