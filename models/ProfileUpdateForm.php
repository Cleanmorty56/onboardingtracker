<?php

namespace app\models;

use yii\base\Model;
use app\models\User;

class ProfileUpdateForm extends Model
{
    public $email;
    public $user;

    public function rules()
    {
        return [
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'required'],
            [['email'], 'unique', 'targetClass' => User::class, 'message' => 'Пользователь с такой эл. почтой уже имеется в системе.'],
        ];
    }

    public function update()
    {
        if ($this->validate()){
            $this->user->email = $this->email;
            return $this->user->save();
        } else {
            return false;
        }
    }
}