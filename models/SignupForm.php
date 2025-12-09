<?php

namespace app\models;

use yii\base\Model;

class SignupForm extends Model
{
    public $name;
    public $email;
    public $role;
    public $password;

    public function rules()
    {
        return [
            [['name', 'email', 'password', 'role'], 'required'],
            ['email', 'email'],
            ['password', 'string', 'min' => 8],
            ['role', 'integer', 'max' => 1],
            [['email'], 'unique', 'targetClass' => User::class, 'message' => 'Пользователь с такой эл. почтой уже имеется в системе.'],
            [['name'], 'match', 'pattern' => '/^[а-яА-ЯёЁ\s\-]+$/u', 'message' => 'Только символы русского алфавита!'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'email' => 'Email',
            'password' => 'Пароль',
            'role' => 'Выберите роль'
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->role = $this->role;
        $user->setPassword($this->password);
        return $user->save() ? $user : null;
    }
}