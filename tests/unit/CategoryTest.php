<?php

namespace tests\unit\models;

use app\models\Category;

class CategoryTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        Category::deleteAll();
    }

    public function testCreateCategory()
    {
        $category = new Category();
        $category->name = 'IT-категория';

        $this->assertTrue($category->save());
        $this->assertNotNull($category->id);
        $this->assertEquals('IT-категория', $category->name);

        $foundCategory = Category::findOne($category->id);
        $this->assertNotNull($foundCategory);
        $this->assertEquals('IT-категория', $foundCategory->name);
    }

    public function testCreateCategoryWithoutName()
    {
        $category = new Category();
        $category->name = '';

        $this->assertFalse($category->save());
        $this->assertArrayHasKey('name', $category->errors);
        $this->assertNotEmpty($category->errors['name']);
    }

    public function testUpdateCategoryWithEmptyName()
    {
        $category = new Category();
        $category->name = 'IT-категория';
        $category->save();

        $category->name = '';
        $this->assertFalse($category->save());
        $this->assertArrayHasKey('name', $category->errors);
        $this->assertNotEmpty($category->errors['name']);

        $sameCategory = Category::findOne($category->id);
        $this->assertEquals('IT-категория', $sameCategory->name);
    }


    public function testCreateCategoriesWithSameName()
    {
        $category1 = new Category();
        $category1->name = 'Безопасность';
        $this->assertTrue($category1->save());

        $category2 = new Category();
        $category2->name = 'Безопасность';
        $this->assertTrue($category2->save());

        $this->assertNotEquals($category1->id, $category2->id);
    }

    public function testReadCategoryById()
    {
        $category = new Category();
        $category->name = 'Безопасность';
        $category->save();

        $foundCategory = Category::findOne($category->id);
        $this->assertNotNull($foundCategory);
        $this->assertEquals('Безопасность', $foundCategory->name);
        $this->assertEquals($category->id, $foundCategory->id);
    }

    public function testReadAllCategories()
    {
        $categoriesData = [
            ['name' => 'Безопасность'],
            ['name' => 'IT-категория'],
            ['name' => 'Процессы']
        ];

        foreach ($categoriesData as $data) {
            $category = new Category();
            $category->name = $data['name'];
            $category->save();
        }

        $allCategories = Category::find()->all();

        $this->assertCount(3, $allCategories);

        $names = array_map(function($cat) {
            return $cat->name;
        }, $allCategories);

        $this->assertContains('Безопасность', $names);
        $this->assertContains('IT-категория', $names);
        $this->assertContains('Процессы', $names);
    }

    public function testReadNonExistentCategory()
    {
        $foundCategory = Category::findOne(999999);
        $this->assertNull($foundCategory);
    }

    public function testUpdateCategory()
    {
        $category = new Category();
        $category->name = 'Старое название';
        $category->save();

        $category->name = 'Процессы';
        $this->assertTrue($category->save());

        $updatedCategory = Category::findOne($category->id);
        $this->assertEquals('Процессы', $updatedCategory->name);
    }

    public function testUpdateUnsavedCategory()
    {
        $category = new Category();
        $category->name = 'Новая категория';

        $this->assertTrue($category->save());
        $this->assertNotNull($category->id);
    }

    public function testDeleteCategory()
    {
        $category = new Category();
        $category->name = 'Категория для удаления';
        $category->save();

        $categoryId = $category->id;

        $this->assertNotNull(Category::findOne($categoryId));

        $this->assertEquals(1, $category->delete());

        $deletedCategory = Category::findOne($categoryId);
        $this->assertNull($deletedCategory);
    }

    public function testDeleteNonExistentCategory()
    {
        $category = new Category();
        $category->id = 999999;

        $this->assertEquals(0, $category->delete());
    }

    public function testDeleteAllCategories()
    {
        for ($i = 1; $i <= 5; $i++) {
            $category = new Category();
            $category->name = "Категория $i";
            $category->save();
        }

        $this->assertCount(5, Category::find()->all());

        $deletedCount = Category::deleteAll();
        $this->assertEquals(5, $deletedCount);

        $this->assertCount(0, Category::find()->all());
    }

    public function testFindCategoryByName()
    {
        $category = new Category();
        $category->name = 'Тестовая категория';
        $category->save();

        $foundCategory = Category::find()
            ->where(['name' => 'Тестовая категория'])
            ->one();

        $this->assertNotNull($foundCategory);
        $this->assertEquals('Тестовая категория', $foundCategory->name);
        $this->assertEquals($category->id, $foundCategory->id);
    }

    public function testFullCrudCycle()
    {
        $category = new Category();
        $category->name = 'Новая категория';
        $this->assertTrue($category->save());
        $id = $category->id;

        $savedCategory = Category::findOne($id);
        $this->assertNotNull($savedCategory);
        $this->assertEquals('Новая категория', $savedCategory->name);

        $savedCategory->name = 'Обновленная категория';
        $this->assertTrue($savedCategory->save());

        $updatedCategory = Category::findOne($id);
        $this->assertEquals('Обновленная категория', $updatedCategory->name);

        $this->assertEquals(1, $updatedCategory->delete());

        $deletedCategory = Category::findOne($id);
        $this->assertNull($deletedCategory);
    }

    public function testCategoryAttributes()
    {
        $category = new Category();

        $attributes = $category->attributes();
        $this->assertContains('id', $attributes);
        $this->assertContains('name', $attributes);

        $category->name = 'Тестовая категория';
        $this->assertEquals('Тестовая категория', $category->name);

        $category->setAttributes([
            'name' => 'Массовое присвоение'
        ]);
        $this->assertEquals('Массовое присвоение', $category->name);
    }

    public function testValidationScenarios()
    {
        $category = new Category();

        $scenarios = $category->scenarios();
        $this->assertArrayHasKey('default', $scenarios);

        $category->name = null;
        $this->assertFalse($category->validate(['name']));

        $category->name = 'Валидное имя';
        $this->assertTrue($category->validate(['name']));
    }
}