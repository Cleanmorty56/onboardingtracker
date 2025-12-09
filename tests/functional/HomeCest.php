<?php

namespace functional;

use yii\helpers\Url;

class HomeCest
{
    public function ensureThatHomePageWorks(\FunctionalTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/index'));
        $I->see('Добро пожаловать на Onboarding Tracker!', 'h1');
        $I->see('Здесь вы сможете обучаться и следить за своими успехами!', 'p');

        $I->seeElement('a', ['href' => '#modules']);
        $I->see('К модулям');

        $I->seeElement('#modules');
    }
}