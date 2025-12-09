<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "progress".
 *
 * @property int $id
 * @property int $user_id
 * @property int $module_id
 * @property string $completed_at
 *
 * @property Module $module
 * @property User $user
 */
class Progress extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'progress';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'module_id'], 'required'],
            [['user_id', 'module_id'], 'integer'],
            [['completed_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['module_id'], 'exist', 'skipOnError' => true, 'targetClass' => Module::class, 'targetAttribute' => ['module_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'Сотрудник'),
            'module_id' => Yii::t('app', 'Модуль'),
            'completed_at' => Yii::t('app', 'Окончено'),
        ];
    }

    /**
     * Gets query for [[Module]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(Module::class, ['id' => 'module_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
