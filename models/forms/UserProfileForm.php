<?php namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;
use yii\base\UserException;

class UserProfileForm extends Model {
    public $user_id;
    public $status;
    public $email;
    public $username;
    public $previous_password;
    public $password;
    public $password_repeat;

    public function rules()
    {
        return [
            ['user_id', 'integer'],
            [
                [
                    'user_id',
                    'email', 
                    'username',
                ], 
                'required'
            ],
            [
                [
                    'previous_password',
                    'password',
                    'password_repeat',
                    'status',
                ],
                'string'
            ],
            [
                'password_repeat', 
                'compare', 
                'compareAttribute' => 'password', 
                'message' => "Passwords don't match"
            ],
            ['email', 'email'],
        ];
    }

    /**
     * Register user with credentials
     */
    public function saveProfile() 
    {
        $currentUser = Yii::$app->user->getIdentity();
        if (
            $currentUser && 
            $currentUser->id == $this->user_id
        ) {
            $currentUser->status = $this->status;
            $currentUser->username = $this->username;
            $currentUser->email = $this->email;
            if ($this->previous_password) {
                if (!$currentUser->validatePassword($this->previous_password)) {
                    throw new UserException('Old password is entered incorrectly');
                }
                if (!$this->password == $this->password_repeat) {
                    throw new UserException('Passwords missmatch!');   
                }
                $currentUser->password = password_hash(
                    $this->password, 
                    PASSWORD_BCRYPT
                );
            }
            if (!$currentUser->save()) {
                $errors = $currentUser->getErrors();
                $first = array_shift(
                    $errors
                );
                throw new UserException(
                    array_shift(
                        $first
                    )
                );
            }
            return true;
        }   
        return false;
    }
}