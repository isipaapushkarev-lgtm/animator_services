<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
$pageTitle='Контакты'; $active='contacts';
require __DIR__ . '/partials/header.php';
?>
<section class="section"><div class="container"><span class="eyebrow">Ответим и подберём программу</span><h1 class="section-title">БУДЕМ НА СВЯЗИ</h1><div class="contact-grid" style="margin-top:34px"><aside class="panel"><h2>Контакты ЯРКО!</h2><div class="contact-list"><a href="tel:+79000000000"><b>Телефон</b>+7 900 000-00-00</a><a href="mailto:hello@yarko-night.ru"><b>Почта</b>hello@yarko-night.ru</a><span><b>Адрес</b>Москва, ул. Праздничная, 12</span><span><b>Режим работы</b>ежедневно, 09:00–21:00</span></div><a class="button button--pink" style="margin-top:28px" href="mailto:hello@yarko-night.ru">Написать нам</a><p class="eyebrow" style="margin-top:24px">Выезжаем по Москве и области</p></aside><div id="map" aria-label="Интерактивная карта с местоположением ЯРКО!"></div></div></div></section>
<section class="section--compact"><div class="container panel"><h2>Как работает карта</h2><p class="lead">Интерактивная карта создана с помощью JavaScript-библиотеки Leaflet. Картографические тайлы и данные загружаются через API OpenStreetMap, маркер показывает адрес организации. Карту можно перемещать и масштабировать.</p></div></section>
<?php require __DIR__ . '/partials/footer.php'; ?>

