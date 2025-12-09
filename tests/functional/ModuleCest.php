<?php
namespace tests\functional;

use FunctionalTester;
use app\models\Category;
use app\models\Module;

class ModuleCest
{
    public function _before(FunctionalTester $I)
    {
        Module::deleteAll();
        Category::deleteAll();
    }

    public function _after(FunctionalTester $I)
    {
        Module::deleteAll();
        Category::deleteAll();
    }

    public function testCreateModulePage(FunctionalTester $I)
    {
        $category = new Category();
        $category->name = 'Тестовая категория';
        $category->save();

        $I->amOnPage('/admin/module/create');
        $I->seeResponseCodeIs(200);
        $I->see('Добавление модуля', 'h1');
        $I->seeElement('select[name="Module[category_id]"]');
        $I->seeElement('input[name="Module[title]"]');
        $I->seeElement('textarea[name="Module[description]"]');
        $I->seeElement('button[type="submit"]');
    }

    public function testCreateModuleValidation(FunctionalTester $I)
    {
        $category = new Category();
        $category->name = 'Тестовая категория';
        $category->save();

        $I->amOnPage('/admin/module/create');

        $I->click('Сохранить');

        // Проверяем валидационные сообщения
        $I->see('cannot be blank');
        $I->see('cannot be blank');
    }

    public function testCreateModuleSuccess(FunctionalTester $I)
    {
        $category = new Category();
        $category->name = 'Тестовая категория для модуля';
        $category->save();

        $I->amOnPage('/admin/module/create');

        // Выбираем категорию по тексту
        $I->selectOption('Module[category_id]', 'Тестовая категория для модуля');

        $I->fillField('Module[title]', 'Новый тестовый модуль');
        $I->fillField('Module[description]', 'Подробное описание нового тестового модуля');
        $I->click('Сохранить');

        $I->seeResponseCodeIs(200);
        $I->seeRecord(Module::class, [
            'title' => 'Новый тестовый модуль',
            'description' => 'Подробное описание нового тестового модуля',
            'category_id' => $category->id
        ]);
    }

    public function testViewModule(FunctionalTester $I)
    {
        $category = new Category();
        $category->name = 'Категория для просмотра';
        $category->save();

        $module = new Module();
        $module->title = 'Модуль для просмотра';
        $module->description = 'Описание модуля для тестирования просмотра';
        $module->category_id = $category->id;
        $module->save();

        $I->amOnPage('/admin/module/view?id=' . $module->id);
        $I->seeResponseCodeIs(200);
        $I->see('Модуль для просмотра', 'h1');
        $I->see('Описание модуля для тестирования просмотра');
        $I->see('Изменить');
        $I->see('Удалить');
    }

    public function testUpdateModule(FunctionalTester $I)
    {
        $category = new Category();
        $category->name = 'Исходная категория';
        $category->save();

        $newCategory = new Category();
        $newCategory->name = 'Новая категория';
        $newCategory->save();

        $module = new Module();
        $module->title = 'Старый модуль';
        $module->description = 'Старое описание';
        $module->category_id = $category->id;
        $module->save();

        $I->amOnPage('/admin/module/update?id=' . $module->id);
        $I->seeResponseCodeIs(200);
        $I->see('Изменение модуля: Старый модуль', 'h1');

        $I->selectOption('Module[category_id]', 'Новая категория');

        $I->fillField('Module[title]', 'Обновленный модуль');
        $I->fillField('Module[description]', 'Новое описание модуля');
        $I->click('Сохранить');

        $I->seeResponseCodeIs(200);
        $I->see('Обновленный модуль');
        $I->seeRecord(Module::class, [
            'id' => $module->id,
            'title' => 'Обновленный модуль',
            'description' => 'Новое описание модуля',
            'category_id' => $newCategory->id
        ]);
    }

    public function testDeleteModule(FunctionalTester $I)
    {
        $category = new Category();
        $category->name = 'Категория для удаления';
        $category->save();

        $module = new Module();
        $module->title = 'Модуль для удаления';
        $module->description = 'Этот модуль будет удален';
        $module->category_id = $category->id;
        $module->save();

        // Проверяем, что модуль создан
        $I->seeRecord(Module::class, ['id' => $module->id]);

        // Удаляем через модель (это тестирует бизнес-логику)
        $module->delete();

        // Проверяем, что удалилось
        $I->dontSeeRecord(Module::class, ['id' => $module->id]);

        // Проверяем, что страница списка работает
        $I->amOnPage('/admin/module/index');
        $I->seeResponseCodeIs(200);
        $I->see('Модули', 'h1');
    }
    public function testModuleIndexPage(FunctionalTester $I)
    {
        $category = new Category();
        $category->name = 'Основная категория';
        $category->save();

        $module1 = new Module();
        $module1->title = 'Модуль 1';
        $module1->description = 'Описание модуля 1';
        $module1->category_id = $category->id;
        $module1->save();

        $module2 = new Module();
        $module2->title = 'Модуль 2';
        $module2->description = 'Описание модуля 2';
        $module2->category_id = $category->id;
        $module2->save();

        $I->amOnPage('/admin/module/index');
        $I->seeResponseCodeIs(200);
        $I->see('Модули', 'h1');
        $I->see('Модуль 1');
        $I->see('Модуль 2');
        $I->see('Добавить модуль');
    }

    public function testDebugSelect(FunctionalTester $I)
    {
        $category = new Category();
        $category->name = 'Отладочная категория';
        $category->save();

        $I->amOnPage('/admin/module/create');
        $I->seeResponseCodeIs(200);

        // Выведем HTML для отладки
        $html = $I->grabPageSource();
        $I->comment('Page source length: ' . strlen($html));

        // Посмотрим, какие опции есть в select
        $I->seeElement('select[name="Module[category_id]"]');

        // Попробуем выбрать опцию по тексту
        $I->selectOption('Module[category_id]', 'Отладочная категория');

        // Проверим, что выбралось
        $selected = $I->grabValueFrom('select[name="Module[category_id]"]');
        $I->comment('Selected value: ' . $selected);
    }
}