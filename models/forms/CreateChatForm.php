<?php namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\UserChats;
use app\models\Chat;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class CreateChatForm extends Model 
{
	public $users;
	public $name;

	/**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // users and name are both required
            [['users', 'name'], 'required'],
            ['name', 'string', 'min' => 4, 'max' => 14],
        ];
    }

    /**
     * Returns main field template
     */
    public function getFieldTemplate() 
    {
        return '{label}{input}<span class="input-error">{error}</span>'; 
    }

    public function create() 
    {
        $chat = new Chat();
        $chat->name = $this->name;
        $chat->save();

        foreach ($this->users as $uid) {
            $userChat = new UserChats();
            $userChat->user_id = $uid;
            $userChat->chat_id = $chat->id;
            $userChat->save();
        }

        $userChatSelf = new UserChats();
        $userChatSelf->user_id = Yii::$app->user->id;;
        $userChatSelf->chat_id = $chat->id;
        $userChatSelf->save();

        return $chat->id;
    }
}