<?php

namespace tests\unit\models;

use app\models\Module;
use app\models\Category;

class ModuleTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        Module::deleteAll();
        Category::deleteAll();

        // Создаем тестовую категорию для модулей
        $category = new Category();
        $category->name = 'IT-категория';
        $category->save();

        $this->categoryId = $category->id;
    }

    public function testCreateModule()
    {
        $module = new Module();
        $module->category_id = $this->categoryId;
        $module->title = 'Основы Python';
        $module->description = 'Базовый курс по программированию на Python';

        $this->assertTrue($module->save());
        $this->assertNotNull($module->id);
        $this->assertEquals('Основы Python', $module->title);
        $this->assertEquals('Базовый курс по программированию на Python', $module->description);

        $foundModule = Module::findOne($module->id);
        $this->assertNotNull($foundModule);
        $this->assertEquals('Основы Python', $foundModule->title);
    }

    public function testCreateModuleWithoutCategory()
    {
        $module = new Module();
        $module->title = 'Основы Python';
        $module->description = 'Базовый курс';

        $this->assertFalse($module->save());
        $this->assertArrayHasKey('category_id', $module->errors);

        $hasError = false;
        foreach ($module->errors['category_id'] as $errorMessage) {
            if (strpos($errorMessage, 'Категория') !== false) {
                $hasError = true;
                break;
            }
        }
        $this->assertTrue($hasError);
    }

    public function testCreateModuleWithoutTitle()
    {
        $module = new Module();
        $module->category_id = $this->categoryId;
        $module->description = 'Базовый курс';

        $this->assertFalse($module->save());
        $this->assertArrayHasKey('title', $module->errors);

        $hasError = false;
        foreach ($module->errors['title'] as $errorMessage) {
            if (strpos($errorMessage, 'Название') !== false) {
                $hasError = true;
                break;
            }
        }
        $this->assertTrue($hasError);
    }

    public function testCreateModuleWithoutDescription()
    {
        $module = new Module();
        $module->category_id = $this->categoryId;
        $module->title = 'Основы Python';

        $this->assertFalse($module->save());
        $this->assertArrayHasKey('description', $module->errors);

        $hasError = false;
        foreach ($module->errors['description'] as $errorMessage) {
            if (strpos($errorMessage, 'Описание') !== false) {
                $hasError = true;
                break;
            }
        }
        $this->assertTrue($hasError);
    }

    public function testCreateModulesWithSameTitle()
    {
        $module1 = new Module();
        $module1->category_id = $this->categoryId;
        $module1->title = 'Основы Python';
        $module1->description = 'Описание 1';
        $this->assertTrue($module1->save());

        $module2 = new Module();
        $module2->category_id = $this->categoryId;
        $module2->title = 'Основы Python';
        $module2->description = 'Описание 2';
        $this->assertTrue($module2->save());

        $this->assertNotEquals($module1->id, $module2->id);
    }

    public function testReadModuleById()
    {
        $module = new Module();
        $module->category_id = $this->categoryId;
        $module->title = 'Основы Python';
        $module->description = 'Базовый курс';
        $module->save();

        $foundModule = Module::findOne($module->id);

        $this->assertNotNull($foundModule);
        $this->assertEquals('Основы Python', $foundModule->title);
        $this->assertEquals('Базовый курс', $foundModule->description);
        $this->assertEquals($module->id, $foundModule->id);
    }

    public function testReadAllModules()
    {
        $modulesData = [
            [
                'title' => 'Основы Python',
                'description' => 'Базовый курс Python'
            ],
            [
                'title' => 'Конфиденциальная информация',
                'description' => 'Работа с конфиденциальными данными'
            ],
            [
                'title' => 'CSS',
                'description' => 'Стилизация веб-страниц'
            ]
        ];

        foreach ($modulesData as $data) {
            $module = new Module();
            $module->category_id = $this->categoryId;
            $module->title = $data['title'];
            $module->description = $data['description'];
            $module->save();
        }

        $allModules = Module::find()->all();

        $this->assertCount(3, $allModules);

        $titles = array_map(function($module) {
            return $module->title;
        }, $allModules);

        $this->assertContains('Основы Python', $titles);
        $this->assertContains('Конфиденциальная информация', $titles);
        $this->assertContains('CSS', $titles);
    }

    public function testReadNonExistentModule()
    {
        $foundModule = Module::findOne(999999);
        $this->assertNull($foundModule);
    }

    public function testUpdateModule()
    {
        $module = new Module();
        $module->category_id = $this->categoryId;
        $module->title = 'Старое название';
        $module->description = 'Старое описание';
        $module->save();

        $module->title = 'Основы Python';
        $module->description = 'Обновленное описание';
        $this->assertTrue($module->save());

        $updatedModule = Module::findOne($module->id);
        $this->assertEquals('Основы Python', $updatedModule->title);
        $this->assertEquals('Обновленное описание', $updatedModule->description);
    }

    public function testUpdateModuleWithEmptyTitle()
    {
        $module = new Module();
        $module->category_id = $this->categoryId;
        $module->title = 'Основы Python';
        $module->description = 'Описание';
        $module->save();

        $module->title = '';
        $this->assertFalse($module->save());
        $this->assertArrayHasKey('title', $module->errors);

        $hasError = false;
        foreach ($module->errors['title'] as $errorMessage) {
            if (strpos($errorMessage, 'Название') !== false) {
                $hasError = true;
                break;
            }
        }
        $this->assertTrue($hasError);

        $sameModule = Module::findOne($module->id);
        $this->assertEquals('Основы Python', $sameModule->title);
    }

    public function testUpdateModuleWithEmptyDescription()
    {
        $module = new Module();
        $module->category_id = $this->categoryId;
        $module->title = 'Основы Python';
        $module->description = 'Описание';
        $module->save();

        $module->description = '';
        $this->assertFalse($module->save());
        $this->assertArrayHasKey('description', $module->errors);

        $hasError = false;
        foreach ($module->errors['description'] as $errorMessage) {
            if (strpos($errorMessage, 'Описание') !== false) {
                $hasError = true;
                break;
            }
        }
        $this->assertTrue($hasError);
    }

    public function testUpdateUnsavedModule()
    {
        $module = new Module();
        $module->category_id = $this->categoryId;
        $module->title = 'Новый модуль';
        $module->description = 'Описание';

        $this->assertTrue($module->save());
        $this->assertNotNull($module->id);
    }

    public function testDeleteModule()
    {
        $module = new Module();
        $module->category_id = $this->categoryId;
        $module->title = 'Модуль для удаления';
        $module->description = 'Описание';
        $module->save();

        $moduleId = $module->id;

        $this->assertNotNull(Module::findOne($moduleId));

        $this->assertEquals(1, $module->delete());

        $deletedModule = Module::findOne($moduleId);
        $this->assertNull($deletedModule);
    }

    public function testDeleteNonExistentModule()
    {
        $module = new Module();
        $module->id = 999999;

        $this->assertEquals(0, $module->delete());
    }

    public function testDeleteAllModules()
    {
        for ($i = 1; $i <= 5; $i++) {
            $module = new Module();
            $module->category_id = $this->categoryId;
            $module->title = "Модуль $i";
            $module->description = "Описание $i";
            $module->save();
        }

        $this->assertCount(5, Module::find()->all());

        $deletedCount = Module::deleteAll();
        $this->assertEquals(5, $deletedCount);

        $this->assertCount(0, Module::find()->all());
    }

    public function testFindModuleByTitle()
    {
        $module = new Module();
        $module->category_id = $this->categoryId;
        $module->title = 'Тестовый модуль';
        $module->description = 'Описание';
        $module->save();

        $foundModule = Module::find()
            ->where(['title' => 'Тестовый модуль'])
            ->one();

        $this->assertNotNull($foundModule);
        $this->assertEquals('Тестовый модуль', $foundModule->title);
        $this->assertEquals($module->id, $foundModule->id);
    }

    public function testFullCrudCycle()
    {
        $module = new Module();
        $module->category_id = $this->categoryId;
        $module->title = 'Новый модуль';
        $module->description = 'Описание';
        $this->assertTrue($module->save());
        $id = $module->id;

        $savedModule = Module::findOne($id);
        $this->assertNotNull($savedModule);
        $this->assertEquals('Новый модуль', $savedModule->title);

        $savedModule->title = 'Обновленный модуль';
        $this->assertTrue($savedModule->save());

        $updatedModule = Module::findOne($id);
        $this->assertEquals('Обновленный модуль', $updatedModule->title);

        $this->assertEquals(1, $updatedModule->delete());

        $deletedModule = Module::findOne($id);
        $this->assertNull($deletedModule);
    }

    public function testModuleAttributes()
    {
        $module = new Module();

        $attributes = $module->attributes();
        $this->assertContains('id', $attributes);
        $this->assertContains('category_id', $attributes);
        $this->assertContains('title', $attributes);
        $this->assertContains('description', $attributes);

        $module->title = 'Тестовый модуль';
        $module->description = 'Тестовое описание';
        $this->assertEquals('Тестовый модуль', $module->title);
        $this->assertEquals('Тестовое описание', $module->description);

        $module->setAttributes([
            'title' => 'Новый заголовок',
            'description' => 'Новое описание'
        ]);
        $this->assertEquals('Новый заголовок', $module->title);
        $this->assertEquals('Новое описание', $module->description);
    }

    public function testValidationScenarios()
    {
        $module = new Module();

        $scenarios = $module->scenarios();
        $this->assertArrayHasKey('default', $scenarios);

        $module->title = null;
        $this->assertFalse($module->validate(['title']));

        $module->title = 'Валидное название';
        $module->description = 'Валидное описание';
        $this->assertTrue($module->validate(['title', 'description']));
    }

    public function testModuleWithCategoryRelation()
    {
        $module = new Module();
        $module->category_id = $this->categoryId;
        $module->title = 'Модуль с категорией';
        $module->description = 'Описание';
        $module->save();

        $foundModule = Module::findOne($module->id);
        $this->assertNotNull($foundModule);
        $this->assertEquals($this->categoryId, $foundModule->category_id);

        $category = Category::findOne($this->categoryId);
        $this->assertNotNull($category);
        $this->assertEquals('IT-категория', $category->name);
    }
}