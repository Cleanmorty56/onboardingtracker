<?php
namespace tests\unit;

use Yii;
use yii\db\Transaction;
use yii\test\ActiveFixture;
use yii\test\FixtureTrait;

class TestCase extends \Codeception\Test\Unit
{
    use FixtureTrait;

    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * @var array
     */
    public $fixtures = [];

    /**
     * Метод выполняется перед каждым тестом
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Начинаем транзакцию для изоляции тестов
        $this->transaction = Yii::$app->db->beginTransaction();

        // Загружаем фикстуры если есть
        if (!empty($this->fixtures)) {
            $this->loadFixtures();
        }
    }

    /**
     * Метод выполняется после каждого теста
     */
    protected function tearDown(): void
    {
        // Откатываем транзакцию
        if ($this->transaction !== null && $this->transaction->isActive) {
            $this->transaction->rollBack();
        }

        parent::tearDown();
    }

    /**
     * Создает экземпляр приложения для тестов
     */
    protected function mockApplication()
    {
        Yii::$app->set('db', [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2basic_test',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ]);
    }

    /**
     * Создает тестового пользователя
     */
    protected function loginTestUser()
    {
        $user = new \app\models\User();
        $user->id = 1;
        $user->email = 'd@gmail.com';
        $user->name = 'Дмитрий';

        Yii::$app->user->login($user, 3600);

        return $user;
    }

    /**
     * Создает и заполняет модель
     */
    protected function createModel($class, $attributes = [])
    {
        $model = new $class();
        $model->attributes = $attributes;

        if (!$model->save()) {
            $this->fail('Failed to create model: ' . print_r($model->errors, true));
        }

        return $model;
    }

    /**
     * Проверяет наличие ошибок валидации
     */
    protected function assertModelHasErrors($model, $attributes = [])
    {
        $this->assertTrue($model->hasErrors(), 'Model should have errors');

        foreach ($attributes as $attribute) {
            $this->assertArrayHasKey(
                $attribute,
                $model->errors,
                "Model should have error for attribute: $attribute"
            );
        }
    }

    /**
     * Проверяет отсутствие ошибок валидации
     */
    protected function assertModelNoErrors($model)
    {
        $this->assertFalse(
            $model->hasErrors(),
            'Model should not have errors. Errors: ' . print_r($model->errors, true)
        );
    }
}