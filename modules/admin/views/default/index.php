<?php

use app\models\User;
use yii\helpers\Url;

?>
<?php $regularUsersCount = User::find()
->where(['!=', 'role', '1'])
->count();
?>
<div class="admin-default-index">
    <div class="admin-home">
        <div class="title-block">
            <h1 class="zag-admin">Административная панель</h1>
        </div>
        <div class="admin-item">
            <p>Всего сотрудников: <?= $regularUsersCount ?> </p>
        </div>
        <div class="admin-buttons">
            <a class="btn-form" href="<?= Url::toRoute(['module/index']);?>">Модули</a>
            <a class="btn-form" href="<?= Url::toRoute(['category/index']);?>">Категории</a>
            <a class="btn-form" href="<?= Url::toRoute(['progress/index']);?>">Прогресс сотрудников</a>
        </div>
    </div>
</div>
