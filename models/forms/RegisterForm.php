<?php namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;
use yii\base\UserException;

class RegisterForm extends Model {
	public $email;
	public $username;
	public $password;
	public $password_repeat;

	public function rules()
    {
        return [
            [
                ['email', 'username', 'password', 'password_repeat'], 
                'required'
            ],
			['password', 'string', 'min' => 6],
            ['username', 'string', 'min' => 4, 'max' => 14],
            [
                'password_repeat', 
                'compare', 
                'compareAttribute'=>'password', 
                'message'=>"Passwords don't match"
            ],
            ['email', 'email'],
        ];
    }

    /**
     * Returns main field template
     */
    public function getFieldTemplate() 
    {
        return '<div class="row"><div class="col-md-6 col-md-offset-3 col-sm-12">{label}{input}<span class="input-error">{error}</span></div></div>'; 
    }

    /**
     * Register user with credentials
     */
    public function register() 
    {
        if ($this->password == $this->password_repeat) {
            $userExists = User::findByEmail($this->email);
            if ($userExists) {
                throw new UserException("Error: this email has already been taken");
            }
            $userExists = User::findByUsername($this->username);
            if ($userExists) {
                throw new UserException("Error: this username has already been taken");
            }
            $user = new User();
            $user->email = $this->email;
            $user->username = $this->username;
            $user->password = password_hash(
                $this->password, 
                PASSWORD_BCRYPT
            );
            $user->save();
            Yii::$app->user->login($user, 0);
            return true;
        } else {
            throw new UserException("Error: Passwords mismatch");
        }
    }
}