<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url; ?>
<div class="form-reg">
    <div class="title-reg">
        <h1 class="zag-admin">Изменение email'а</h1>
    </div>
    <?php $form = ActiveForm::begin();

    echo $form->field($model, 'email')->textInput(['maxLength' => true]);
    ?>
    <div class="form-group">
        <?php echo Html::submitButton('Сохранить', ['class' => 'btn-form', 'name' => 'login-button']) ?>
        <a class="btn-form" href="<?= Url::toRoute(['/employee/']); ?>"><-Назад</a>
    </div>

    <?php ActiveForm::end() ?>
</div>

