<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
$pageTitle='Главная'; $active='home'; $items=programs();
require __DIR__ . '/partials/header.php';
?>
<section class="hero"><div class="container hero-grid">
    <div><span class="eyebrow">Тематические программы для детей 6–14 лет</span><h1 class="title">ПРАЗДНИК НАЧИНАЕТСЯ ЗДЕСЬ</h1><p class="lead">Безопасное ночное шоу с оригинальными маскотами, музыкой, квестами и ярким финалом.</p><div class="hero-actions"><a class="button button--pink" href="order.php">Заказать праздник</a><a class="button button--ghost" href="animators.php">Познакомиться с героями</a></div><div class="stats"><div class="stat"><b>186</b>проведённых праздников</div><div class="stat"><b>4,9</b>средняя оценка</div><div class="stat"><b>4</b>сценических героя</div></div></div>
    <div class="stage-card" aria-label="Фирменный маскот Фред на ночной сцене"><span class="stage-star">★</span><span class="bear accent-yellow" aria-hidden="true"><i></i></span></div>
</div></section>
<section class="section"><div class="container"><span class="eyebrow">Выберите программу</span><h2 class="section-title">ПРОГРАММЫ</h2><div class="card-grid">
<?php foreach($items as $item): ?><article class="card program-card accent-<?= e($item['accent']) ?>"><h3><?= e($item['name']) ?></h3><p><?= e($item['summary']) ?></p><div class="meta"><span class="tag"><?= e($item['duration_minutes']) ?> минут</span><span class="tag"><?= e($item['heroes_count']) ?> <?= (int)$item['heroes_count']===1?'герой':'героя' ?></span><span class="tag"><?= e($item['age_group']) ?></span></div><div class="card-actions"><span class="price">от <?= format_price((float)$item['price']) ?></span><a class="button button--small" href="order.php?program=<?= (int)$item['id'] ?>">Заказать</a></div></article><?php endforeach; ?>
</div></div></section>
<section class="section--compact"><div class="container banner"><div><span class="eyebrow">Ночная сцена</span><h2>СОБЕРИ КОМАНДУ.<br>ВКЛЮЧИ СВЕТ.</h2></div><a class="button button--cyan" href="prices.php">Сравнить форматы</a></div></section>
<?php require __DIR__ . '/partials/footer.php'; ?>

