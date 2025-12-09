<?php
namespace app\models;
use yii\base\Model;
use app\models\User;
use Yii;

class PasswordChangeForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $newPasswordRepeat;

    private $user;

    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'newPasswordRepeat'], 'required'],
            ['newPassword', 'string', 'min' => 8],
            ['newPasswordRepeat', 'compare', 'compareAttribute' => 'newPassword'],
            ['oldPassword', 'validateOldPassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword' => 'Старый пароль',
            'newPassword' => 'Новый пароль',
            'newPasswordRepeat' => 'Повтор нового пароля',
        ];
    }

    public function validateOldPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findOne(Yii::$app->user->identity->getId());
            if (!$user || !$user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Неверно введен старый пароль');
            }
        }
    }

    public function changePassword()
    {
        if ($this->validate()) {
            $user = Yii::$app->user->identity;
            $user->setPassword($this->newPassword);
            return $user->save();
        }
        return false;
    }
}