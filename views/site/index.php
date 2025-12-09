<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\helpers\Url;

?>
<div class="site-index">
    <div class="title-home">
        <h1 class="zag-admin">Добро пожаловать на Onboarding Tracker!</h1>
        <p>Здесь вы сможете обучаться и следить за своими успехами!</p>
        <div class="button-home">
            <a href="#modules"
               class="btn-form">К модулям</a>
        </div>
    </div>
    <div class="advantages">
        <div class="adv-card">
            <div class="img-adv">
                <?= Html::img('@web/images/29.05.jpg', [
                    'alt' => 'around-adv',
                    'class' => 'rounded-image'
                ]) ?>
            </div>
            <div class="text-adv">
                <h5 class="zag-adv">Быстрое и качественное обучение сотрудников</h5>
                <p style="text-align: left">Мы понимаем, что время – ценный ресурс. Поэтому мы предлагаем программы обучения, разработанные для
                    того, чтобы ваши сотрудники осваивали новые навыки и знания максимально оперативно, без потери
                    качества. Наши методики нацелены на практическое применение, чтобы каждый участник мог сразу же
                    применять полученные знания в работе, повышая свою продуктивность и общую эффективность команды.</p>
            </div>
        </div>
        <div class="adv-card">
            <div class="text-adv">
                <h5 class="zag-adv">Повышение своей квалификации</h5>
                <p style="text-align: left">Повышение квалификации — это не только способ улучшить свои профессиональные навыки, но и возможность
                    расширить кругозор, повысить уверенность в себе и открыть новые карьерные горизонты. Вложение в
                    собственное развитие всегда оправдывает себя, и чем раньше вы начнете, тем больше возможностей
                    откроется перед вами в будущем.</p>
            </div>
            <div class="img-adv">
                <?= Html::img('@web/images/laaO.sBFzbmHFz7rrkPacA.jpg', [
                    'alt' => 'around-adv',
                    'class' => 'rounded-image'
                ]) ?>
            </div>

        </div>
    </div>
    <div id="modules" class="title-block">
        <h1 class="zag-admin">Модули</h1>
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
    <?php if ($isAdmin): ?>
    <div class="admin-actions"
    ">
    <a href="<?= Url::to(['/admin/module/create']) ?>" class="btn-form">
        Добавить новый модуль
    </a>
</div>
<?php endif; ?>
<div class="employee-modules">
    <div class="module-cards">
        <?php foreach ($modules as $module): ?>
            <?php
            $isCompleted = !$isGuest && in_array($module->id, $completedModules);
            ?>
            <div class="module-item <?= $isCompleted ? 'completed' : '' ?>">
                <div class="zag-module">
                    <p><?= $module->title ?></p>
                </div>
                <p><?= $module->description ?></p>
                <p>Категория: <?= $module->category->name ?></p>
                <div class="module-button">
                    <?php if ($isGuest): ?>
                        <a href="<?= Url::to(['site/login']) ?>"
                           class="btn-form">Войдите для отметки</a>
                    <?php elseif ($isAdmin): ?>

                        <div class="admin-action-buttons">
                            <a href="<?= Url::to(['/admin/module/update', 'id' => $module->id]) ?>"
                               class="btn-form">Редактировать</a>
                            <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $module->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Вы уверены в том, чтобы удалить этот модуль?'),
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    <?php elseif (!$isCompleted): ?>
                        <a class="btn-form"
                           href="<?= Url::to(['complete', 'id' => $module->id]) ?>">
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