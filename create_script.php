<?php
$email = $_POST['mail'];
$password = hash('sha256', $_POST['password']);
$status = $_POST['status'];
$img = $_FILES['profile_image'];

function validateImage($img, $to): array{
    if ($img['error'] > 0) {
        $str = 'Ошибка: ';
        switch ($img['error']) {
            case 1: $str .= 'Размер файла больше upload_max_filesize';
            break;
            case 2: $str .= 'Размер файла больше max_file_size';
            break;
            case 3: $str .= 'Загружена только часть файла';
            break;
            case 4: $str .= 'Файл не загружен';
            break;
            case 6: $str .= 'Загрузка невозможна: не задан временный каталог';
            break;
            case 7: $str .= 'Загрузка не выполнена: невозможна запись на диск';
            break;
        }
        return [1, $str];
    }
    
    if ($img['type'] != 'image/jpg' &
    $img['type'] != 'image/jpeg' &&
    $img['type'] != 'image/png' &&
    $img['type'] != 'image/webp'){
        return [1, 'Ошибка: Файл не является изображением'];
    }

    if (is_uploaded_file($img['tmp_name'])) {
        if (!move_uploaded_file($img['tmp_name'], $to)) {
            return [1, 'Ошибка: Невозможно переместить файл в необходимый каталог'];
        }
    } else {
        return [1, 'Ошибка: Возможна атака через загрузку файла'];
    }
    return [0, 'Изображение валидно'];
}

// mysql
$stmt = $mysql->prepare("INSERT INTO users(email, password, pfp, status) VALUES (:email, :pswrd, :pfp, :status)");
$lastUserQ = $mysql->query("SELECT id FROM users ORDER BY id LIMIT 1", PDO::FETCH_COLUMN, 0);
$lastUserQ->execute();
$lastUser = $lastUserQ->fetch();

$name = $lastUser['id']+1;
$created = time();
$to = '';

if(!isset($img) || $img == 'default'){  
    $to = 'static/user-default.png';
} else {
    $to = 'static/user/'.$name.$created.'.png';
}

$imgVal = validateImage($img, $to);

if ($imgVal[0] == 1){
    $_SESSION['response'] = $imgVal;
    die;
}

$stmt->bindValue(":email", $email);
$stmt->bindValue(":pswrd", $password);
$stmt->bindValue(":pfp", str_replace('static/', '', $to));
$stmt->bindValue(":status", $status);
$res = $stmt->execute();

// sqlite
// $stmt = $sqlite->prepare("INSERT INTO users(email, password) VALUES (:email, :pswrd)");

// $stmt->bindValue(":email", $email);
// $stmt->bindValue(":pswrd", $password);

// $res = $stmt->execute();

if ($res){
    $_SESSION['response'] = [0, "Создание пользователя успешно"];
    header('Location: users');
}
else{
    $_SESSION['response'] = [1, "Ошибка: создание пользователя с ошибками"];
    header('Location: users');
}