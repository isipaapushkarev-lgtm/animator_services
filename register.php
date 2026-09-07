<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
if(current_user())redirect('account.php');
$pageTitle='Регистрация'; $active='register';
if(is_post()){
    verify_csrf();$name=trim((string)($_POST['name']??''));$phone=trim((string)($_POST['phone']??''));$email=strtolower(trim((string)($_POST['email']??'')));$password=(string)($_POST['password']??'');$confirmation=(string)($_POST['password_confirmation']??'');$errors=[];
    if($name===''||$phone===''||$email===''||$password==='')$errors[]='Заполните все обязательные поля.';
    if(!filter_var($email,FILTER_VALIDATE_EMAIL))$errors[]='Введите корректный email.';
    if(!preg_match('/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/',$phone))$errors[]='Введите телефон по маске.';
    if(!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/',$password))$errors[]='Пароль должен содержать минимум 8 символов, латинскую букву и цифру.';
    if($password!==$confirmation)$errors[]='Пароли не совпадают.';
    $pdo=db();if($pdo){$s=$pdo->prepare('SELECT COUNT(*) FROM users WHERE email=?');$s->execute([$email]);if((int)$s->fetchColumn()>0)$errors[]='Пользователь с таким email уже существует.';}
    if(!$errors){if($pdo){$s=$pdo->prepare('INSERT INTO users(name,phone,email,password_hash) VALUES(?,?,?,?)');$s->execute([$name,$phone,$email,password_hash($password,PASSWORD_DEFAULT)]);$_SESSION['user_id']=(int)$pdo->lastInsertId();}else{$_SESSION['user_id']=999;$_SESSION['demo_user']=['id'=>999,'name'=>$name,'phone'=>$phone,'email'=>$email,'role'=>'client'];}flash('success','Аккаунт создан. Добро пожаловать!');redirect('account.php');}
    flash('error',implode(' ',array_unique($errors)));
}
require __DIR__ . '/partials/header.php';
?>
<section class="section"><div class="container auth-layout"><aside class="auth-art"><span class="stage-star">★</span><h1>ОДИН АККАУНТ —<br>МНОГО ПРАЗДНИКОВ</h1><p>Сохраняйте заказы, фотографии и историю мероприятий.</p></aside><form class="panel" method="post" data-validate><span class="eyebrow">Новый пользователь</span><h1 class="section-title">СОЗДАНИЕ АККАУНТА</h1><?= csrf_field() ?><div class="form-grid"><div class="field"><label for="name">Имя *</label><input id="name" name="name" value="<?= e($_POST['name']??'') ?>" required></div><div class="field"><label for="phone">Телефон *</label><input id="phone" name="phone" data-phone placeholder="+7 (999) 999-99-99" value="<?= e($_POST['phone']??'') ?>" required></div><div class="field"><label for="email">Email *</label><input id="email" type="email" name="email" value="<?= e($_POST['email']??'') ?>" required></div><div class="field"><label for="password">Пароль *</label><input id="password" type="password" name="password" required><small>Минимум 8 символов, буква и цифра.</small></div><div class="field field--full"><label for="password_confirmation">Повторите пароль *</label><input id="password_confirmation" type="password" name="password_confirmation" required></div></div><div class="form-actions"><button class="button button--pink">Создать аккаунт</button></div><p>Уже есть аккаунт? <a class="eyebrow" href="login.php">Войти</a></p></form></div></section>
<?php require __DIR__ . '/partials/footer.php'; ?>

