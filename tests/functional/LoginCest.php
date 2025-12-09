<?php

namespace tests\functional;

use FunctionalTester;

class LoginCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
    }

    public function testLoginWithInvalidEmail(FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[email]' => 'invalid-email',
            'LoginForm[password]' => 'password123',
        ]);
        try {
            $I->see('Значение «Email» не является правильным email адресом.');
        } catch (\Exception $e) {
            $I->see('Email is not a valid email address.');
        }
    }

    public function testLoginWithEmptyCredentials(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->seeElement('#login-form');
        $I->submitForm('#login-form', []);

        $I->seeInCurrentUrl('login');
        $I->seeElement('#login-form');
    }

    public function testLoginWithWrongCredentials(FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[email]' => 'd@gmail.com',
            'LoginForm[password]' => 'wrongpassword',
        ]);
        $I->see('Неверный email или пароль.');
    }
    public function testLoginSuccessfully(FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[email]' => 'd@gmail.com',
            'LoginForm[password]' => 'admin123',
        ]);

        $I->dontSee('Авторизация');
        $I->dontSee('Заполните эти поля для авторизации:');

        $I->see('Главная');
        $I->see('Административная панель');
        $I->see('d@gmail.com');
    }
}