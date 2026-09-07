<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
$pageTitle='Аниматоры'; $active='animators'; $items=animators();
require __DIR__ . '/partials/header.php';
?>
<section class="section"><div class="container"><span class="eyebrow">Оригинальная команда маскотов</span><h1 class="section-title">АНИМАТОРЫ НОЧНОЙ СЦЕНЫ</h1><p class="lead">Каждый артист проходит внутреннее обучение, знает возрастные сценарии и правила безопасной работы с детьми.</p><div class="card-grid card-grid--four" style="margin-top:36px">
<?php foreach($items as $item): ?><article class="card accent-<?= e($item['accent']) ?>"><span class="bear" aria-hidden="true"><i></i></span><h2><?= e($item['character_name']) ?></h2><span class="eyebrow"><?= e($item['name']) ?></span><p><?= e($item['bio']) ?></p><div class="meta"><span class="tag">Опыт <?= (int)$item['experience_years'] ?> лет</span><span class="tag">★ <?= e((string)$item['rating']) ?></span></div><a class="button button--small" href="order.php?animator=<?= (int)$item['id'] ?>">Выбрать героя</a></article><?php endforeach; ?>
</div></div></section>
<section class="section--compact"><div class="container banner"><div><h2>ГЕРОЙ ПОД БУДУЩИЙ ПРАЗДНИК</h2><p>Поможем подобрать характер, программу и продолжительность.</p></div><a class="button button--ghost" href="contacts.php">Получить консультацию</a></div></section>
<?php require __DIR__ . '/partials/footer.php'; ?>

