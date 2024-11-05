<?php
if ($user_info){
    $id = $_GET['id'];
    $query = $mysql->prepare('DELETE FROM users WHERE id = :id');
    $query->bindParam('id', $id);
    $res = $query->execute();
    if ($res){
        $_SESSION['response'] = [0, 'Удаление успешно'];
        header('Location: users');
    }
    else{
        $_SESSION['response'] = [1, 'Ошибка удаления данных'];
        header('Location: users');
    }
}
else{
    header('Location: ../'.$dir);
}