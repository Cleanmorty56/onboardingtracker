<?php

namespace tests\functional;

use app\models\Category;
use FunctionalTester;

class CategoryCest
{
    public function _before(FunctionalTester $I)
    {
        Category::deleteAll();
    }

    public function testIndexPage(FunctionalTester $I)
    {
        $I->amOnPage('/admin/category/index');
        $I->seeResponseCodeIs(200);
        $I->see('Категории', 'h1');
        $I->see('Добавить категорию');
    }

    public function testCreateCategory(FunctionalTester $I)
    {
        $I->amOnPage('/admin/category/create');
        $I->seeResponseCodeIs(200);
        $I->see('Добавление категории', 'h1');

        $I->fillField('Category[name]', 'Новая категория');
        $I->click('Сохранить');

        $I->seeResponseCodeIs(200);
        $I->see('Новая категория');
        $I->seeRecord(Category::class, ['name' => 'Новая категория']);
    }

    public function testViewCategory(FunctionalTester $I)
    {
        $category = new Category();
        $category->name = 'Test Category View';
        $category->save();

        $I->amOnPage('/admin/category/view?id=' . $category->id);
        $I->seeResponseCodeIs(200);
        $I->see('Test Category View', 'h1');
        $I->see('Изменить');
        $I->see('Удалить');
    }

    public function testUpdateCategory(FunctionalTester $I)
    {
        $category = new Category();
        $category->name = 'Old Name';
        $category->save();

        $I->amOnPage('/admin/category/update?id=' . $category->id);
        $I->seeResponseCodeIs(200);
        $I->see('Изменение категории: Old Name', 'h1');

        $I->fillField('Category[name]', 'Updated Name');
        $I->click('Сохранить');

        $I->seeResponseCodeIs(200);
        $I->see('Updated Name');
        $I->seeRecord(Category::class, ['name' => 'Updated Name']);
    }

    public function testEmptyCategoryName(FunctionalTester $I)
    {
        $I->amOnPage('/admin/category/create');
        $I->fillField('Category[name]', '');
        $I->click('Сохранить');

        $I->seeResponseCodeIs(200);
        $I->see('cannot be blank.');
    }
}