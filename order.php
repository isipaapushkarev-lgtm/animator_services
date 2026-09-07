<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
$pageTitle='Заказ'; $active='order'; $programItems=programs(); $animatorItems=animators();
$selectedProgram=(int)($_GET['program']??$_POST['program_id']??0);$selectedAnimator=(int)($_GET['animator']??$_POST['animator_id']??0);
if(is_post()){
    verify_csrf();
    $data=[
        'customer_name'=>trim((string)($_POST['customer_name']??'')),'phone'=>trim((string)($_POST['phone']??'')),
        'email'=>trim((string)($_POST['email']??'')),'event_date'=>(string)($_POST['event_date']??''),
        'event_time'=>(string)($_POST['event_time']??''),'address'=>trim((string)($_POST['address']??'')),
        'children_age'=>trim((string)($_POST['children_age']??'')),'children_count'=>(int)($_POST['children_count']??0),
        'comment'=>trim((string)($_POST['comment']??'')),'program_id'=>(int)($_POST['program_id']??0),
        'animator_id'=>(int)($_POST['animator_id']??0),
    ];
    $errors=[];
    foreach(['customer_name','phone','email','event_date','event_time','address','children_age'] as $key){if($data[$key]==='')$errors[]='Заполните обязательные поля.';}
    if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))$errors[]='Введите корректный email.';
    if(!preg_match('/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/',$data['phone']))$errors[]='Введите телефон по маске +7 (999) 999-99-99.';
    if($data['event_date']<date('Y-m-d'))$errors[]='Дата мероприятия не может быть в прошлом.';
    if($data['children_count']<1||$data['children_count']>60)$errors[]='Количество детей должно быть от 1 до 60.';
    $chosen=array_values(array_filter($programItems,fn($p)=>(int)$p['id']===$data['program_id']));
    if(!$chosen)$errors[]='Выберите программу.';
    if(!$errors){
        $price=(float)$chosen[0]['price'];$pdo=db();$orderId=random_int(1000,9999);
        if($pdo){$statement=$pdo->prepare('INSERT INTO orders(user_id,program_id,animator_id,customer_name,phone,email,event_date,event_time,address,children_age,children_count,comment,total_price) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)');$statement->execute([current_user()['id']??null,$data['program_id'],$data['animator_id']?:null,$data['customer_name'],$data['phone'],$data['email'],$data['event_date'],$data['event_time'],$data['address'],$data['children_age'],$data['children_count'],$data['comment'],$price]);$orderId=(int)$pdo->lastInsertId();}
        flash('success','Заказ №'.$orderId.' принят. Мы свяжемся с вами для подтверждения.');redirect('order.php');
    }
    flash('error',implode(' ',array_unique($errors)));
}
$user=current_user();
require __DIR__ . '/partials/header.php';
?>
<section class="section"><div class="container"><span class="eyebrow">Заполните параметры мероприятия</span><h1 class="section-title">ОФОРМЛЕНИЕ ЗАКАЗА</h1><div class="form-shell" style="margin-top:32px"><form class="panel" method="post" data-validate><div class="form-message"></div><?= csrf_field() ?><div class="form-grid"><div class="field"><label for="program_id">Программа *</label><select id="program_id" name="program_id" required><option value="">Выберите программу</option><?php foreach($programItems as $item): ?><option value="<?= (int)$item['id'] ?>" <?= $selectedProgram===(int)$item['id']?'selected':'' ?>><?= e($item['name']) ?> — <?= format_price((float)$item['price']) ?></option><?php endforeach; ?></select></div><div class="field"><label for="animator_id">Аниматор</label><select id="animator_id" name="animator_id"><option value="">Подобрать автоматически</option><?php foreach($animatorItems as $item): ?><option value="<?= (int)$item['id'] ?>" <?= $selectedAnimator===(int)$item['id']?'selected':'' ?>><?= e($item['character_name']) ?> — <?= e($item['name']) ?></option><?php endforeach; ?></select></div><div class="field"><label for="customer_name">Имя *</label><input id="customer_name" name="customer_name" value="<?= e($_POST['customer_name']??$user['name']??'') ?>" required></div><div class="field"><label for="phone">Телефон *</label><input id="phone" name="phone" data-phone placeholder="+7 (999) 999-99-99" value="<?= e($_POST['phone']??$user['phone']??'') ?>" required></div><div class="field"><label for="email">Email *</label><input id="email" name="email" type="email" value="<?= e($_POST['email']??$user['email']??'') ?>" required></div><div class="field"><label for="event_date">Дата *</label><input id="event_date" name="event_date" type="date" min="<?= date('Y-m-d') ?>" value="<?= e($_POST['event_date']??'') ?>" required></div><div class="field"><label for="event_time">Время *</label><input id="event_time" name="event_time" type="time" value="<?= e($_POST['event_time']??'16:00') ?>" required></div><div class="field"><label for="address">Адрес *</label><input id="address" name="address" value="<?= e($_POST['address']??'') ?>" required></div><div class="field"><label for="children_age">Возраст детей *</label><input id="children_age" name="children_age" placeholder="Например, 7–9 лет" value="<?= e($_POST['children_age']??'') ?>" required></div><div class="field"><label for="children_count">Количество детей *</label><input id="children_count" name="children_count" type="number" min="1" max="60" value="<?= e($_POST['children_count']??10) ?>" required></div><div class="field field--full"><label for="comment">Комментарий</label><textarea id="comment" name="comment"><?= e($_POST['comment']??'') ?></textarea></div></div><div class="form-actions"><button class="button button--pink">Продолжить оформление</button></div></form><aside class="panel"><span class="eyebrow">Ваш праздник</span><span class="bear accent-yellow" style="margin:38px auto;display:block"><i></i></span><h2>Что будет дальше</h2><p>Менеджер проверит дату, подтвердит программу и закрепит свободного аниматора.</p><ul><li>Ответ в течение 30 минут</li><li>Цена фиксируется после подтверждения</li><li>Предоплата — только после согласования</li></ul></aside></div></div></section>
<?php require __DIR__ . '/partials/footer.php'; ?>

