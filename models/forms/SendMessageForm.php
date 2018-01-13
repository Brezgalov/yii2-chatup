<?php namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class SendMessageForm extends Model 
{
    public $user_id;
    public $chat_id;
	public $text;

	/**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['text', 'user_id', 'chat_id'], 'required'],
            ['text', 'string'],
            [['user_id', 'chat_id'], 'integer'],
        ];
    }

    public function send()
    {
        $date = date('Y-m-d H:i:s');
        $message = new Messages();
        $message->date = $date;
        $message->user_id = $this->user_id;
        $message->chat_id = $this->chat_id;
        $message->text = $this->text;
        $message->save();
        return $message->id;
    }
}