<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;?>
<div class="form-reg">
    <div class="title-reg">
        <h2 class="zag-admin">Регистрация</h2>
        <p>Заполните эти поля для регистрации:</p>
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
    ]) ?>
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'email')->textInput() ?>
    <?= $form->field($model, 'role')->radioList([
        '0' => 'Сотрудник',
        '1' => 'Администратор'
    ]) ?>
    <?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group">
        <div class="d-flex justify-content-center">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn-form']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
