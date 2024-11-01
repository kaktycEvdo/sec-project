<?php
if ($user_info && isset($_GET['id'])){
    $query = $mysql->prepare('SELECT email, status FROM users WHERE id = :id');
    $query->bindParam('id', $_GET['id']);
    $query->execute();
    $res = $query->fetch(PDO::FETCH_ASSOC);

    echo '<form action="edit_script?id='.$_GET['id'].'" id="auth_form" method="post">
        <h1>Редактирование пользователя</h1>
        <div>
            <label for="email">Электронная почта:</label>
            <input type="email" name="email" value="'.$res['email'].'" autocomplete="new-password">
            <label for="name">Пароль:</label>
            <input type="password" name="password" autocomplete="new-password">
            <label for="status">Статус:</label>
            <select name="status" id="status">
                <option value="1" '.(isset($user_info["status"]) && $user_info["status"] == 1 ? "selected" : "").'>В сети</option>
                <option value="2" '.(isset($user_info["status"]) && $user_info["status"] == 2 ? "selected" : "").'>Не в сети</option>
                <option value="3" '.(isset($user_info["status"]) && $user_info["status"] == 3 ? "selected" : "").'>Не беспокоить</option>
            </select>
        </div>
        <div>
            <input type="submit" value="Редактировать">
        </div>
    </form>';
}
else{
    header('Location: ../sec-project');
}