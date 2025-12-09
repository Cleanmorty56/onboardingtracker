<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

?>
<div class="container">
    <div class="form-reg">
        <div class="title-reg">
            <h1 class="zag-admin">Изменить пароль</h1>
        </div>
    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'oldPassword')->passwordInput(['maxLength' => true]); ?>
    <?php echo $form->field($model, 'newPassword')->passwordInput(['maxLength' => true]); ?>
    <?php echo $form->field($model, 'newPasswordRepeat')->passwordInput(['maxLength' => true]); ?>

    <div class="form-group">
        <?php echo Html::submitButton('Изменить пароль',['class' => 'btn-form']) ?>
        <a class="btn-form" href="<?= Url::toRoute(['/employee/']); ?>"><-Назад</a>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>