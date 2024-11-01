<?php
function validateImage($img): array{
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

    return [0, 'Изображение валидно'];
}

function convertImage($original, $towidth, $toheight) {
    //jpg, png
    $ext = getimagesize($original['tmp_name'])['mime'];
    $size = getimagesize($original['tmp_name']);

    if (preg_match('/jpg|jpeg/i', $ext))
        $imageTemp = imagecreatefromjpeg($original['tmp_name']);
    else if (preg_match('/png/i', $ext))
        $imageTemp = imagecreatefrompng($original['tmp_name']);
    else
        return 0;

    $ratio = $size[0]/$size[1]; // width / height
    $width = 0;
    $height = 0;
    if ($ratio > 1){
        $width  = $towidth;
        $heigth = $toheight / $ratio;
    } else {
        $width = $towidth*$ratio;
        $heigth = $toheight;
    }

    $resizedImg = imagecreatetruecolor($width, $heigth);
    imagecopyresampled($resizedImg, $imageTemp, 0,0,0,0,$width, $heigth, $size[0], $size[1]);

    return $resizedImg;
}

$email = $_POST['mail'];
$password = hash('sha256', $_POST['password']);
$status = $_POST['status'];
$img = $_FILES['profile_image'];

// mysql
$stmt = $mysql->prepare("INSERT INTO users(email, password, pfp, status) VALUES (:email, :pswrd, :pfp, :status)");
$lastUserQ = $mysql->query("SELECT id FROM users ORDER BY id LIMIT 1", PDO::FETCH_COLUMN, 0);
$lastUserQ->execute();
$lastUser = $lastUserQ->fetch();

$name = $lastUser['id']+1;
$created = time();
$to = '';
$toMin = '';

if(!isset($img) || $img == 'default'){  
    $to = 'static/user-default.png';  
    $toMin = 'static/user-default.png';
} else {
    $to = 'static/user/'.$name.$created.'.png';  
    $toMin = 'static/user/min-'.$name.$created.'.png';
}

$imgVal = validateImage($img);

if ($imgVal[0] == 1){
    $_SESSION['response'] = $imgVal;
    header('Location: ../sec-project');
    die;
}

$newImgMin = convertImage($img, 100, 100);
$newImg = convertImage($img, 800, 800);

$imgVal1 = imagepng($newImgMin, $toMin);
$imgVal2 = imagepng($newImg, $to);

if(!$imgVal1 || !$imgVal2){
    $_SESSION['response'] = [1, "Ошибка конвертации изображения"];
    header('Location: ../sec-project');
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