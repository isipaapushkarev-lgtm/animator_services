<?php
declare(strict_types=1);
require_once __DIR__ . '/functions.php';
$pageTitle='Отзывы'; $active='reviews'; $items=reviews_list();
if(is_post()){
    verify_csrf(); $user=require_auth();
    $orderId=(int)($_POST['order_id']??0); $rating=(int)($_POST['rating']??0); $title=trim((string)($_POST['title']??'')); $body=trim((string)($_POST['body']??''));
    if(!$orderId||$rating<1||$rating>5||$title===''||$body===''){flash('error','Заполните все поля отзыва.');redirect('reviews.php');}
    $pdo=db();
    if($pdo){$check=$pdo->prepare("SELECT id,event_date FROM orders WHERE id=? AND user_id=? AND status='completed'");$check->execute([$orderId,$user['id']]);$order=$check->fetch();if(!$order){flash('error','Отзыв доступен только для завершённого заказа.');redirect('reviews.php');}$statement=$pdo->prepare('INSERT INTO reviews(user_id,order_id,rating,title,body,event_date) VALUES(?,?,?,?,?,?)');try{$statement->execute([$user['id'],$orderId,$rating,$title,$body,$order['event_date']]);flash('success','Спасибо! Отзыв опубликован.');}catch(PDOException $e){flash('error','К этому заказу отзыв уже добавлен.');}}
    redirect('reviews.php');
}
require __DIR__ . '/partials/header.php';
?>
<section class="section"><div class="container"><span class="eyebrow">Впечатления после праздников</span><h1 class="section-title">ОТЗЫВЫ РОДИТЕЛЕЙ</h1><div class="review-summary"><b class="rating-big">4,9</b><span class="stars">★★★★★</span><span>186 опубликованных отзывов</span><?php if(current_user()): ?><a class="button button--ghost" href="#review-form">Оставить отзыв</a><?php else: ?><a class="button button--ghost" href="login.php">Войти для отзыва</a><?php endif; ?></div><div class="card-grid" style="margin-top:28px">
<?php foreach($items as $item): ?><article class="card review-card"><span class="eyebrow"><?= e($item['user_name']) ?> · <span class="stars"><?= str_repeat('★',(int)$item['rating']) ?></span></span><h2><?= e($item['title']) ?></h2><blockquote><?= e($item['body']) ?></blockquote><small><?= e(date('d.m.Y',strtotime($item['event_date']))) ?></small></article><?php endforeach; ?>
</div>
<?php if($user=current_user()): $pdo=db();$orders=[];if($pdo){$s=$pdo->prepare("SELECT id,event_date FROM orders WHERE user_id=? AND status='completed' ORDER BY event_date DESC");$s->execute([$user['id']]);$orders=$s->fetchAll();} ?>
<div class="panel" id="review-form" style="margin-top:32px"><h2>Добавить отзыв</h2><form method="post" data-validate><div class="form-grid"><?= csrf_field() ?><div class="field"><label for="order_id">Завершённый заказ</label><select id="order_id" name="order_id" required><option value="">Выберите заказ</option><?php foreach($orders as $order): ?><option value="<?= (int)$order['id'] ?>">№<?= (int)$order['id'] ?> от <?= e(date('d.m.Y',strtotime($order['event_date']))) ?></option><?php endforeach; ?></select></div><div class="field"><label for="rating">Оценка</label><select id="rating" name="rating" required><option value="5">5 — отлично</option><option value="4">4 — хорошо</option><option value="3">3 — нормально</option><option value="2">2 — плохо</option><option value="1">1 — очень плохо</option></select></div><div class="field field--full"><label for="title">Заголовок</label><input id="title" name="title" maxlength="120" required></div><div class="field field--full"><label for="body">Текст отзыва</label><textarea id="body" name="body" required></textarea></div></div><div class="form-actions"><button class="button button--pink">Опубликовать</button></div></form></div><?php endif; ?>
</div></section>
<?php require __DIR__ . '/partials/footer.php'; ?>

