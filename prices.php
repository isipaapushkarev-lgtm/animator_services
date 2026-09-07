<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
$pageTitle='Цены'; $active='prices'; $items=programs();
require __DIR__ . '/partials/header.php';
?>
<section class="section"><div class="container"><span class="eyebrow">Три пакета без скрытых доплат</span><h1 class="section-title">ФОРМАТЫ ПРАЗДНИКА</h1><p class="lead">Цена зависит от продолжительности, количества героев и выбранных дополнительных услуг.</p><div class="pricing-grid" style="margin-top:42px">
<?php foreach($items as $index=>$item): ?><article class="price-card <?= $index===1?'featured':'' ?>"><span class="eyebrow"><?= ['Мини','Ярко','Максимум'][$index] ?></span><h2><?= e($item['name']) ?></h2><div class="price"><?= format_price((float)$item['price']) ?></div><p><?= (int)$item['heroes_count'] ?> героя · <?= (int)$item['duration_minutes'] ?> минут</p><ul><li>Сценарий и ведущий</li><li>Музыкальное сопровождение</li><li>Световой реквизит</li><li>Финальное фото</li><?php if($index>0): ?><li>Подарок имениннику</li><?php endif; ?></ul><a class="button <?= $index===1?'button--pink':'' ?>" href="order.php?program=<?= (int)$item['id'] ?>">Выбрать пакет</a></article><?php endforeach; ?>
</div><div class="panel" style="margin-top:26px"><h2>Дополнительные услуги</h2><div class="meta"><span class="tag">Аквагрим — 1 500 ₽</span><span class="tag">Фотограф — 3 500 ₽</span><span class="tag">Дополнительный герой — 2 000 ₽</span><span class="tag">Дополнительные 30 минут — 1 500 ₽</span></div></div></div></section>
<?php require __DIR__ . '/partials/footer.php'; ?>

