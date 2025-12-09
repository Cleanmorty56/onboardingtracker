<?php

namespace unit;

use app\models\User;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        $user = User::findOne(1);
        verify($user)->notEmpty();
        verify($user->email)->equals('d@gmail.com');
        verify($user->role)->equals(1);
    }

    public function testFindUserByEmail()
    {
        verify($user = User::findByUsername('d@gmail.com'))->notEmpty();
        verify($user->name)->equals('Дмитрий');
        verify($user->role)->equals(1);

        verify($user = User::findByUsername('grigo@gmail.com'))->notEmpty();
        verify($user->role)->equals(0);

        verify(User::findByUsername('not-exist@example.com'))->empty();
    }

    public function testUserRegistration()
    {
        $user = new User();
        $user->name = 'Новый Тестовый Пользователь';
        $user->email = 'new_test@example.com';
        $user->setPassword('test123');
        $user->role = 0;

        verify($user->save())->true();
        verify($user->name)->equals('Новый Тестовый Пользователь');
        verify($user->role)->equals(0);

        verify(User::findByUsername('new_test@example.com'))->notEmpty();

        User::deleteAll(['email' => 'new_test@example.com']);
    }

    public function testValidatePassword()
    {
        $user = User::findByUsername('d@gmail.com');
        verify($user->validatePassword('admin123'))->true();
        verify($user->validatePassword('wrong_password'))->false();
    }

    public function testUserAttributes()
    {
        $user = User::findOne(1);
        verify($user->id)->equals(1);
        verify($user->name)->equals('Дмитрий');
        verify($user->email)->equals('d@gmail.com');
        verify($user->role)->equals(1);
        verify($user->password)->notEmpty();
    }
}