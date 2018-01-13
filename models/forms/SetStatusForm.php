<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * ContactForm is the model behind the contact form.
 */
class SetStatusForm extends Model
{
    public $status;
    public $user_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // status, user_id are required
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['status'], 'string']
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'status' => '',
            'user_id' => '',
        ];
    }

    public function setStatus() 
    {
        if ($this->user_id) {
            $user = User::findOne(['id' => $this->user_id]);
            $currentUserId = Yii::$app->user->id;
            if (
                $user &&
                $currentUserId = $this->user_id
            ) {
                $user->status = $this->status; 
                return $user->save();   
            }
        }
        return false;
    }
}
