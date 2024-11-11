<?php
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