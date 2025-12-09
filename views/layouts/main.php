<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title>Onboarding Tracker</title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/images/1.png', [
            'alt' => 'logo',
            'style' => 'width: 60px; height: 60px; object-fit: contain;'
        ]),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md position-relative']
    ]);

    // Левый блок - пустой, но занимает место
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto', 'style' => 'min-width: 180px;'],
        'items' => []
    ]);

    // Центральный блок - абсолютное позиционирование по центру
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav position-absolute start-50 translate-middle-x d-flex gap-4'],
        'items' => [
            ['label' => 'Главная', 'url' => ['/site/index']],
            ['label' => 'Личный кабинет', 'url' => ['/employee/'], 'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->role === 0],
            ['label' => 'Административная панель', 'url' => ['/admin/'], 'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->role === 1],
            ['label' => 'Регистрация', 'url' => ['/site/signup'], 'visible' => Yii::$app->user->isGuest],
        ]
    ]);

    // Правый блок - фиксированной ширины
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto', 'style' => 'min-width: 180px; justify-content: flex-end;'],
        'items' => [
            Yii::$app->user->isGuest
                ? ['label' => 'Войти', 'url' => ['/site/login']]
                : '<li class="nav-item">'
                . Html::beginForm(['/site/logout'])
                . Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->email . ')',
                    ['class' => 'nav-link btn btn-link logout', 'style' => 'white-space: nowrap;']
                )
                . Html::endForm()
                . '</li>'
        ]
    ]);

    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3" style="background-color: #0ea5e9">
    <div class="container">
        <div class="row text-muted">
            <div class="text-center text-md-center" style="color: white">&copy; Onboarding
                Tracker <?= date('Y') ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
