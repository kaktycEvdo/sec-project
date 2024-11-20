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

$email = $_POST['mail'];
$password = hash('sha256', $_POST['password']);
// mysql
$stmt = $mysql->prepare("INSERT INTO users(email, password) VALUES (:email, :pswrd)");

$stmt->bindValue(":email", $email);
$stmt->bindValue(":pswrd", $password);
$res = $stmt->execute();

// sqlite
// $stmt = $sqlite->prepare("INSERT INTO users(email, password) VALUES (:email, :pswrd)");

// $stmt->bindValue(":email", $email);
// $stmt->bindValue(":pswrd", $password);

// $res = $stmt->execute();


if ($res){
    $id = $mysql->query("SELECT id FROM users ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_COLUMN);
    $_SESSION['user'] = $id;
    $_SESSION['response'] = [0, "Регистрация успешна"];
    header('Location: '.$dir);
}
else{
    $_SESSION['response'] = [1, "Ошибка 401: Регистрация не удалась"];
    header('Location: '.$dir);
}