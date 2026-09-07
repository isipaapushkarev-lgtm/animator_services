<?php declare(strict_types=1); ?>
</main>
<footer class="site-footer">
    <div class="container footer-grid">
        <div><b>ЯРКО! NIGHT SHOW</b><p>Праздник начинается, когда включается сцена.</p></div>
        <nav aria-label="Навигация в подвале"><a href="index.php">Программы</a><a href="animators.php">Аниматоры</a><a href="prices.php">Цены</a><a href="contacts.php">Контакты</a></nav>
        <div><a href="tel:+79000000000">+7 900 000-00-00</a><a href="mailto:hello@yarko-night.ru">hello@yarko-night.ru</a><small>© 2026 ЯРКО! Обработка данных — по согласию пользователя.</small></div>
    </div>
</footer>
<script src="assets/js/app.js"></script>
<?php if (($active ?? '') === 'contacts'): ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>window.addEventListener('DOMContentLoaded',window.YarkoMap);</script>
<?php endif; ?>
</body>
</html>
