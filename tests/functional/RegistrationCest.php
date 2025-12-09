<?php

namespace tests\functional;

use FunctionalTester;
use app\models\User;

class RegistrationCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('site/signup');
    }

    public function testRegistrationWithEmptyFields(FunctionalTester $I)
    {
        $I->submitForm('#login-form', []);

        $I->see('cannot be blank');
        $I->seeInCurrentUrl('signup');
    }

    public function testRegistrationWithInvalidEmail(FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'SignupForm[name]' => 'Иван Иванов',
            'SignupForm[email]' => 'invalid-email',
            'SignupForm[password]' => 'password123',
            'SignupForm[role]' => '0',
        ]);

        $I->see('Email is not a valid email address.');
        $I->seeInCurrentUrl('signup');
    }

    public function testRegistrationWithLatinName(FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'SignupForm[name]' => 'John Doe',
            'SignupForm[email]' => 'test@example.com',
            'SignupForm[password]' => 'password123',
            'SignupForm[role]' => '0',
        ]);

        $I->see('Только символы русского алфавита!');
        $I->seeInCurrentUrl('signup');
    }

    public function testRegistrationWithShortPassword(FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'SignupForm[name]' => 'Иван Иванов',
            'SignupForm[email]' => 'test@example.com',
            'SignupForm[password]' => '123',
            'SignupForm[role]' => '0',
        ]);

        $I->see('should contain at least 8 characters.');
        $I->seeInCurrentUrl('signup');
    }

    public function testRegistrationWithDuplicateEmail(FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'SignupForm[name]' => 'Иван Иванов',
            'SignupForm[email]' => 'd@gmail.com',
            'SignupForm[password]' => 'password123',
            'SignupForm[role]' => '0',
        ]);


        $I->see('Пользователь с такой эл. почтой уже имеется в системе.');
        $I->seeInCurrentUrl('signup');
    }

    public function testRegistrationSuccessfullyAsEmployee(FunctionalTester $I)
    {
        $uniqueEmail = 'employee_' . time() . '@example.com';

        $I->submitForm('#login-form', [
            'SignupForm[name]' => 'Иван Иванов',
            'SignupForm[email]' => $uniqueEmail,
            'SignupForm[password]' => 'password123',
            'SignupForm[role]' => '0',
        ]);

        $I->dontSeeElement('#login-form');
        $I->dontSeeInCurrentUrl('signup');
        $I->seeInCurrentUrl('/');
    }

    public function testRegistrationSuccessfullyAsAdmin(FunctionalTester $I)
    {
        $uniqueEmail = 'employer_' . time() . '@example.com';

        $I->submitForm('#login-form', [
            'SignupForm[name]' => 'Тест',
            'SignupForm[email]' => $uniqueEmail,
            'SignupForm[password]' => 'password123',
            'SignupForm[role]' => '1',
        ]);

        $I->dontSeeElement('#login-form');
        $I->dontSeeInCurrentUrl('signup');
        $I->seeInCurrentUrl('/');
    }
}