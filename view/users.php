<form method="POST" class="search_user">
    <input type="text" name="search_query">
    <input type="hidden" value="id" name="attr">
    <button class="chosen">id</button>
    <button>email</button>
    <button>created_at</button>
    <button>status</button>
    <input type="submit">
</form>
<section class="users_list">
    <div class="add">
        <a href="create_user">Добавить пользователя...</a>
    </div>
<?php
    function echoUsers($users){
        foreach($users as $_ => $user){
            $status = null;
            switch ($user['status']){
                case 1: $status = 'В сети'; break;
                case 2: $status = 'Не в сети'; break;
                case 3: $status = 'Не беспокоить'; break;
            }
            echo '<div>
            <div><a href="profile?id='.$user['id'].'">'.$user['id'].'</a></div>
            <div>
                <img src="static/'.(isset($user['pfp']) && $user['pfp'] != '' ? $user['pfp'] : 'user-default.png').'" />
            </div>
            <div>
                <div>'.$user['email'].'</div>
                <div>'.date('d.m.Y', strtotime($user['created_at'])).'</div>
                <div>'.$status.'</div>
                <div class="buttons"><a href="edit_user?id='.$user['id'].'">Изменить</a>
                <a href="delete_user?id='.$user['id'].'">Удалить</a></div>
            </div>
            </div>';
        }
    }
    if(isset($_POST['search_query']) && isset($_POST['attr'])){
        $search = $_POST['search_query'];
        $by = $_POST['attr'];
        // $query = $mysql->prepare('SELECT id, email, created_at FROM users WHERE :by LIKE :query');
        // $query->bindParam('by', $by);
        // $query->bindParam('query', $search);
        
        // $query->execute();
        // $users = $query->fetch();

        $users = $mysql->query("SELECT id, email, created_at, status, pfp FROM users WHERE ".$by." LIKE \"%".$search."%\"");

        echoUsers($users);
    }
    else{
        $users = $mysql->query("SELECT id, email, created_at, status, pfp FROM users");

        echoUsers($users);
    }
?>
</section>
<script>
    let btns = document.querySelectorAll('.search_user > button');
    btns.forEach(element => {
        element.addEventListener('click', (e) => {
            e.preventDefault();
            btns.forEach(btn => {
                btn.classList.remove('chosen');
            });
            e.target.classList.add('chosen');
            let value = e.target.innerHTML;
            let input = document.querySelector('input[name="attr"]');
            input.value = value;
        });
    });
</script>