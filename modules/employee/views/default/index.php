<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\helpers\Url;

?>
<div class="employee-default-index">
    <div class="title-block">
        <h1 class="zag-admin">Личный кабинет</h1>
    </div>
    <div class="employee-home">
        <table class="info-employee">
            <tr>
                <th rowspan="3" class="square-avatar">
                    <?= Html::img('@web/images/avatar.png', ['alt' => 'avatar']) ?>
                </th>
                <th>Имя</th>
                <td><?= Html::encode($user->name) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= Html::encode($user->email) ?></td>
            </tr>
            <tr>
                <th>Пройдено модулей</th>
                <td><?= $completed ?> из <?= \app\models\Module::find()->count() ?></td>
            </tr>
        </table>
        <div class="employee-buttons">
            <a class="btn-form" href="<?= Url::toRoute(['/employee/default/pass']); ?>">Изменить пароль</a>
            <a class="btn-form" href="<?= Url::to(['/employee/default/updateemail']); ?>">Изменить email</a>
        </div>
    </div>
    <div class="category-filter">
        <h3>Фильтр по категориям:</h3>
        <div class="filter-buttons">
            <a class="btn-filter <?= !$selectedCategory ? 'active' : '' ?>"
               href="<?= Url::to(['index']) ?>">
                Все категории
            </a>
            <?php foreach ($categories as $category): ?>
                <a class="btn-filter <?= $selectedCategory == $category->id ? 'active' : '' ?>"
                   href="<?= Url::to(['index', 'category_id' => $category->id]) ?>">
                    <?= Html::encode($category->name) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="employee-modules">
        <div class="module-cards">
            <?php foreach ($modules as $module): ?>
                <?php
                $isCompleted = in_array($module->id, $completedModules);
                ?>
                <div class="module-item <?= $isCompleted ? 'completed' : '' ?>">
                    <div class="zag-module">
                        <p><?= $module->title ?></p>
                    </div>
                    <p><?= $module->description ?></p>
                    <p>Категория: <?= $module->category->name ?></p>
                    <div class="module-button">
                        <?php if (!$isCompleted): ?>
                            <a class="btn-form" href="<?= Url::to(['default/complete', 'id' => $module->id]) ?>">
                                Отметить как пройденный
                            </a>
                        <?php else: ?>
                            <button class="btn-form completed" disabled>Модуль пройден</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?= LinkPager::widget([
            'pagination' => $pagination,
            'options' => ['class' => 'pagination justify-content-center'],
            'linkOptions' => ['class' => 'page-link'],
            'disabledListItemSubTagOptions' => ['class' => 'page-link'],
        ]) ?>
    </div>
</div>