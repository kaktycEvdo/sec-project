<?php
    $user = $user_info;
    if($_GET['']);
?>
<section class="profile_info">
<div><img src="static/<?php echo isset($user['pfp']) ? $user['pfp'] : 'user-default.png' ?>" alt="Profile picture"></div>
<div>Пользователь: <?php echo $user['email'] ?></div>
<div>Создан: <?php echo date('d.m.Y', strtotime($user['created_at']))  ?></div>
<div>Статус:
    <?php
        if(isset($user_data['status'])){
            switch ($user_data['status']){
                case 1: echo 'В сети'; break;
                case 2: echo 'Не в сети'; break;
                case 3: echo 'Не беспокоить'; break;
            }
        }
        else{
            echo 'В сети';
        }
    ?>
</div>
</section>