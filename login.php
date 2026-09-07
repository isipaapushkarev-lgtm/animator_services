<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
if(current_user())redirect('account.php');
$pageTitle='Авторизация'; $active='login';
if(is_post()){
    verify_csrf();$email=strtolower(trim((string)($_POST['email']??'')));$password=(string)($_POST['password']??'');$errors=[];
    if(!filter_var($email,FILTER_VALIDATE_EMAIL))$errors[]='Введите корректный email.';if($password==='')$errors[]='Введите пароль.';
    $pdo=db();$user=null;if(!$errors&&$pdo){$s=$pdo->prepare('SELECT * FROM users WHERE email=?');$s->execute([$email]);$candidate=$s->fetch();if($candidate&&password_verify($password,$candidate['password_hash']))$user=$candidate;}elseif(!$errors&&in_array($email,['admin@yarko-night.ru','anna@example.ru'],true)&&$password==='Admin123!'){$user=['id'=>$email[0]==='a'&&str_starts_with($email,'admin')?1:2,'name'=>str_starts_with($email,'admin')?'Администратор':'Анна Морозова','phone'=>'+7 (900) 000-00-00','email'=>$email,'role'=>str_starts_with($email,'admin')?'admin':'client'];}
    if($user){session_regenerate_id(true);$_SESSION['user_id']=(int)$user['id'];$_SESSION['demo_user']=$user;flash('success','Вы вошли в личный кабинет.');redirect(($user['role']??'')==='admin'?'admin.php':'account.php');}
    if(!$errors)$errors[]='Неверный email или пароль.';flash('error',implode(' ',$errors));
}
require __DIR__ . '/partials/header.php';
?>
<section class="section"><div class="container auth-layout"><aside class="auth-art"><span class="bear accent-yellow"><i></i></span><h1>С ВОЗВРАЩЕНИЕМ!</h1><p>Ваши заказы и фотографии уже ждут.</p></aside><form class="panel" method="post" data-validate><span class="eyebrow">Доступ к заказам и фотографиям</span><h1 class="section-title">ВХОД В ЛИЧНЫЙ КАБИНЕТ</h1><?= csrf_field() ?><div class="field"><label for="email">Электронная почта *</label><input id="email" type="email" name="email" value="<?= e($_POST['email']??'') ?>" required></div><div class="field" style="margin-top:18px"><label for="password">Пароль *</label><input id="password" type="password" name="password" required></div><div class="form-actions"><button class="button" style="width:100%">Войти в кабинет</button></div><p>Нет аккаунта? <a class="eyebrow" href="register.php">Зарегистрироваться</a></p><small>Демо: anna@example.ru / Admin123!</small></form></div></section>
<?php require __DIR__ . '/partials/footer.php'; ?>

