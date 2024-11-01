<header>
    <div class="logo">
        <?php
            if (str_replace('/sec-project/', '',  $_SERVER['REQUEST_URI']) == ''){
                echo '<a href="">Главная</a>';
            }
            else{
                echo '<a href="../sec-project">Главная</a>';
            }
        ?>
        
    </div>
    <nav>
        <?php
        session_start();
            if (isset($_SESSION['user'])){
                echo '<a href="edit">Редактировать</a>';
                echo '<a href="profile">Профиль</a>';
                echo '<a href="users">Пользователи</a>';
                echo '<a href="logout">Выйти</a>';
            }
            else{
                echo '<a href="reg">Регистрация</a>';
            }
        ?>
    </nav>
</header>