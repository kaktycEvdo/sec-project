<?php
if(isset($_SESSION['user'])){
    $user = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user'];
    $qChangePassword = $mysql->prepare("UPDATE users SET password = :pswrd WHERE id = ".$user);
    $qChangePFP = $mysql->prepare('UPDATE users SET pfp = :pfp WHERE id = '.$_SESSION['user']);
    $qChangeStatus = $mysql->prepare("UPDATE users SET status = :status WHERE id = ".$user);

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

    function convertImage($original, $size, $towidth, $toheight) {
        //jpg, png
        $ext = $size['mime'];

        if (preg_match('/jpg|jpeg/i', $ext))
            $imageTemp = imagecreatefromjpeg($original);
        else if (preg_match('/png/i', $ext))
            $imageTemp = imagecreatefrompng($original);
        else
            return 0;

        $ratio = $size[0]/$size[1]; // width / height
        $width = 0;
        $height = 0;
        if ($ratio > 1 ) {
            $width  = $towidth;
            $heigth = $toheight / $ratio;
        } else {
            $width = $towidth*$ratio;
            $heigth = $toheight;
        }

        $resizedImg = imagecreatetruecolor($width, $heigth);
        imagecopyresampled($resizedImg, $imageTemp, 0,0,0,0,$width, $heigth, $size[0], $size[1]);
        //starting an output buffer to get the data
        ob_start();
        //here we get the data
        $output = ob_get_clean();

        return $output;
    }

    $res = false;
    if(isset($_POST['password'])){
        $password = hash('sha256', $_POST['password']);

        $qChangePassword->bindValue(":pswrd", $password);

        $res = $qChangePassword->execute();
    }
    if(isset($_POST['status'])){
        $status = $_POST['status'];

        $qChangeStatus->bindValue(":status", $status);

        $res = $qChangeStatus->execute();
    }
    if(isset($_FILES['profile_image'])){
        $img = $_FILES['profile_image'];
        $to;
        $toMin;
        $name = $_SESSION['user'];
        $created = strtotime($user_info['created_at']);

        if(!isset($img) || $img == 'default'){  
            $to = 'static/user-default.png';  
            $toMin = $to;
        } else {
            $to = 'static/user/'.$name.$created.'.png';
            $toMin = 'static/user/'.$name.$created.'-min.png';
        }

        $size = getimagesize($img['tmp_name']);
        $donePicMin = convertImage($img['tmp_name'], $size, 100, 100);
        $donePic = convertImage($img['tmp_name'], $size, 800, 800);

        $res = validateImage($donePic, $to);
        $res = validateImage($donePicMin, $toMin);

        if($res[0] == 1){
            $_SESSION['response'] = $res;
            // header('Location: edit');
            die;
        }
        $to = str_replace('static/', '', $to);
        $qChangePFP->bindParam('pfp', $to);
        if($qChangePFP->execute()) $_SESSION['response'] = [1, 'Изменения успешны'];
    }

    if ($res){
        $_SESSION['response'] = [0, 'Изменение успешно'];
        header('Location: ../sec-project');
    }
    else{
        $_SESSION['response'] = [1, 'Ошибка: не удалось изменить данные пользователя'];
    }
}