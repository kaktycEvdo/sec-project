<?php
if(!isset($_POST['mail']) || !isset($_POST['password'])){
    $_SESSION['response'] = [1, 'Нет введенных данных'];
    header('Location: '.$dir);
    die;
}
if(!isset($_POST['captcha_challenge'])){
    $_SESSION['response'] = [1, 'Не введено решение каптчи'];
    header('Location: '.$dir);
    die;
}
if($_POST['captcha_challenge'] != $_SESSION['captcha_text']){
    $_SESSION['response'] = [1, 'Каптча решена неверно'];
    header('Location: '.$dir);
    die;
}

$mail = $_POST['mail'];
$password = hash('sha256', $_POST['password']);

$stmt = $mysql->prepare("SELECT id FROM users WHERE email = :email and password = :pswrd");

$stmt->bindParam('email', $mail);
$stmt->bindParam('pswrd', $password);

$stmt->execute();
$res = $stmt->fetch();

if ($res){
    $_SESSION['user'] = $res['id'];
    $_SESSION['response'] = [0, 'Авторизация успешна'];
    header('Location: '.$dir);
}
else{
    $_SESSION['response'] = [1, 'Ошибка 401: Неверные данные авторизации'];
    header('Location: '.$dir);
}