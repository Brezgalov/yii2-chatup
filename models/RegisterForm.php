<?php namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model {
	public $email;
	public $username;
	public $password;
	public $password_repeat;

	public function rules()
    {
        return [
            // required fields
            [['email', 'username', 'password', 'password_repeat'], 'required'],
            // password is validated by validatePassword()
            // ['password', 'validatePassword'],
            // password confirm also validates
            // ['password', 'length', 'min'=>6, 'max'=>64, 'on'=>'register, recover'],
            // ['password', 'compare', 'compareAttribute'=>'passwordConfirm', 'on'=>'register, recover'],
			['password', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match"],

            //email is validated as email
            ['email', 'email'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
    	var_dump(1);die();
        // if ($this->password != $this->passwordConfirm) {
            $this->addError($attribute, 'Passwords mismatch!');
        // }
    }

    /**
     * Returns main field template
     */
    public function getFieldTemplate() 
    {
        return '<div class="row"><div class="col-md-6 col-md-offset-3 col-sm-12"><div class="form-group"><label>{label}</label>{input}<span class="input-error">{error}</span></div></div></div>'; 
    }
}