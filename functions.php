<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = ''): string
{
    return $path;
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(24));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = $_POST['csrf'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(419);
        exit('Срок действия формы истёк. Обновите страницу.');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = compact('type', 'message');
}

function flashes(): array
{
    $items = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $items;
}

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }
    $pdo = db();
    if (!$pdo) {
        return $_SESSION['demo_user'] ?? null;
    }
    $statement = $pdo->prepare('SELECT id, name, phone, email, role FROM users WHERE id = ?');
    $statement->execute([(int)$_SESSION['user_id']]);
    return $statement->fetch() ?: null;
}

function require_auth(): array
{
    $user = current_user();
    if (!$user) {
        flash('error', 'Войдите, чтобы открыть личный кабинет.');
        redirect('login.php');
    }
    return $user;
}

function require_admin(): array
{
    $user = require_auth();
    if (($user['role'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('Доступ разрешён только администратору.');
    }
    return $user;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function programs(): array
{
    $pdo = db();
    if ($pdo) {
        return $pdo->query('SELECT * FROM programs WHERE is_active = 1 ORDER BY price')->fetchAll();
    }
    return [
        ['id'=>1,'name'=>'Ночная команда','summary'=>'Сценарий с маскотами, музыкой и световым финалом','age_group'=>'6–12 лет','duration_minutes'=>60,'heroes_count'=>1,'price'=>6900,'accent'=>'pink'],
        ['id'=>2,'name'=>'Аркадный квест','summary'=>'Командные испытания и безопасная ночная атмосфера','age_group'=>'7–12 лет','duration_minutes'=>90,'heroes_count'=>2,'price'=>7900,'accent'=>'cyan'],
        ['id'=>3,'name'=>'Финальное шоу','summary'=>'Большая программа с тремя героями и фотографом','age_group'=>'8–14 лет','duration_minutes'=>120,'heroes_count'=>3,'price'=>12900,'accent'=>'yellow'],
    ];
}

function animators(): array
{
    $pdo = db();
    if ($pdo) {
        return $pdo->query('SELECT * FROM animators WHERE is_active = 1 ORDER BY rating DESC')->fetchAll();
    }
    return [
        ['id'=>1,'name'=>'Артём Волков','character_name'=>'Фред','experience_years'=>5,'rating'=>4.9,'accent'=>'yellow','bio'=>'Ведущий шоу и командных игровых программ.'],
        ['id'=>2,'name'=>'Мария Белова','character_name'=>'Бонни','experience_years'=>4,'rating'=>4.9,'accent'=>'pink','bio'=>'Музыкальный герой и мастер танцевальных игр.'],
        ['id'=>3,'name'=>'Илья Орлов','character_name'=>'Чики','experience_years'=>5,'rating'=>4.9,'accent'=>'yellow','bio'=>'Мастер квестов, загадок и игровых испытаний.'],
        ['id'=>4,'name'=>'София Миронова','character_name'=>'Фокси','experience_years'=>6,'rating'=>4.9,'accent'=>'cyan','bio'=>'Капитан квеста и ведущая командных финалов.'],
    ];
}

function reviews_list(): array
{
    $pdo = db();
    if ($pdo) {
        return $pdo->query("SELECT r.*, u.name AS user_name, p.name AS program_name FROM reviews r JOIN users u ON u.id=r.user_id JOIN orders o ON o.id=r.order_id JOIN programs p ON p.id=o.program_id WHERE r.is_published=1 ORDER BY r.created_at DESC")->fetchAll();
    }
    return [
        ['user_name'=>'Анна','rating'=>5,'title'=>'Дети были в восторге!','body'=>'Артисты приехали вовремя, программа была живой, но не пугающей.','event_date'=>'2026-08-15'],
        ['user_name'=>'Михаил','rating'=>5,'title'=>'Удобно и честно','body'=>'Выбрали пакет онлайн, стоимость не изменилась, всё прошло спокойно.','event_date'=>'2026-08-10'],
        ['user_name'=>'Елена','rating'=>5,'title'=>'Фред стал любимым героем','body'=>'Сын просит снова позвать команду. Фото получили в личном кабинете.','event_date'=>'2026-08-04'],
    ];
}

function format_price(float $price): string
{
    return number_format($price, 0, ',', ' ') . ' ₽';
}

function active_page(string $name, string $current): string
{
    return $name === $current ? 'is-active' : '';
}
