<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "session".
 *
 * @property int $id
 * @property int $user_id
 * @property string $addr
 * @property int $expire
 * @property int $last_write
 * @property resource $data
 */
class Session extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'expire', 'last_write'], 'integer'],
            [['data'], 'string'],
            [['addr'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'addr' => 'Addr',
            'expire' => 'Expire',
            'last_write' => 'Last Write',
            'data' => 'Data',
        ];
    }
}
