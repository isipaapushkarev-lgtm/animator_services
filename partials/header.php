<?php
declare(strict_types=1);
require_once __DIR__ . '/../functions.php';
$pageTitle = $pageTitle ?? APP_NAME;
$active = $active ?? '';
$user = current_user();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ЯРКО! Night Show — тематические программы и аниматоры для детских праздников.">
    <title><?= e($pageTitle) ?> — <?= e(APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@400;500;600;700;800&family=Unbounded:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <?php if ($active === 'contacts'): ?>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <?php endif; ?>
</head>
<body>
<a class="skip-link" href="#content">Перейти к содержимому</a>
<header class="site-header">
    <div class="container header-inner">
        <a class="logo" href="index.php" aria-label="ЯРКО — главная страница">
            <span class="bear bear--small" aria-hidden="true"><i></i></span>
            <span><b>ЯРКО!</b><small>NIGHT SHOW</small></span>
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav"><span></span><span></span><span></span><b>Меню</b></button>
        <nav class="main-nav" id="main-nav" aria-label="Основная навигация">
            <a class="<?= active_page('home',$active) ?>" href="index.php">Главная</a>
            <a class="<?= active_page('animators',$active) ?>" href="animators.php">Аниматоры</a>
            <a class="<?= active_page('prices',$active) ?>" href="prices.php">Цены</a>
            <a class="<?= active_page('order',$active) ?>" href="order.php">Заказ</a>
            <a class="<?= active_page('reviews',$active) ?>" href="reviews.php">Отзывы</a>
            <a class="<?= active_page('contacts',$active) ?>" href="contacts.php">Контакты</a>
            <?php if (!$user): ?><a class="mobile-only" href="login.php">Авторизация</a><a class="mobile-only" href="register.php">Регистрация</a><?php else: ?><a class="mobile-only" href="account.php">Личный кабинет</a><?php endif; ?>
        </nav>
        <div class="account-nav">
            <?php if ($user): ?>
                <a class="button button--ghost" href="account.php">Кабинет</a>
                <?php if (($user['role'] ?? '') === 'admin'): ?><a class="button button--small" href="admin.php">Админ</a><?php endif; ?>
            <?php else: ?>
                <a class="button button--ghost" href="login.php">Войти</a>
                <a class="button button--small" href="register.php">Регистрация</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<main id="content">
<?php foreach (flashes() as $item): ?>
    <div class="container"><div class="alert alert--<?= e($item['type']) ?>" role="status"><?= e($item['message']) ?></div></div>
<?php endforeach; ?>
