<?php namespace app\models;

use Yii;
use yii\base\Model;

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
            ['name', 'string'],
        ];
    }

    /**
     * Returns main field template
     */
    public function getFieldTemplate() 
    {
        return '{label}{input}<span class="input-error">{error}</span>'; 
    }
}