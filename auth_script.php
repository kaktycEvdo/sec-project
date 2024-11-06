<?php
$stmt = $mysql->query("SELECT id FROM users WHERE (email = '".$_POST['mail']."' and password = '".hash('sha256', $_POST['password'])."')");

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