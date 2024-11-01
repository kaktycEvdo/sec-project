<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="static/style.css">
    <title>эээ типа... сайт</title>
</head>
<body>
    <?php include_once 'view/header.php';
    echo '<main>';
    if(isset($_SESSION['response'])){
        $res = $_SESSION['response'];
        echo '<div class="modal '.($res[0] ? "merror" : "msuccess").'">'.$res[1].'</div>';
        $_SESSION['response'] = null;
    }

    $url = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : $_SERVER['REQUEST_URI'];

    switch (str_replace('/unimportant/sec-project/', '',  $url)){
        case '':
        case 'index':
        case 'main':
            include_once 'view/index.html';
            break;
        case 'auth_script':
            require 'connect_to_db.php';
            include_once 'auth_script.php';
            break;
        case 'reg':
            include_once 'view/reg.html';
            break;
        case 'reg_script':
            require 'connect_to_db.php';
            include_once 'reg_script.php';
            break;
        case 'edit':
            require 'connect_to_db.php';
            require 'profile_info.php';
            include_once 'view/edit.php';
            break;
        case 'edit_user':
            require 'connect_to_db.php';
            require 'profile_info.php';
            include_once 'view/edit_user.php';
            break;
        case 'edit_script':
            require 'connect_to_db.php';
            require 'profile_info.php';
            include_once 'edit_script.php';
            break;
        case 'profile':
            require 'connect_to_db.php';
            require 'profile_info.php';
            include_once 'view/profile.php';
            break;
        case 'users':
            require 'connect_to_db.php';
            if(!isset($_SESSION['user'])){
                header('Location: ../sec-project');
            }
            include_once 'view/users.php';
            break;
        case 'phpinfo':
            include_once 'view/phpinfo.php';
            break;
        case 'delete_user':
            require 'connect_to_db.php';
            require 'profile_info.php';
            include_once 'delete.php';
            break;
        case 'create_user':
            require 'connect_to_db.php';
            require 'profile_info.php';
            include_once 'view/create_user.php';
            break;
        case 'create_script':
            require 'connect_to_db.php';
            require 'profile_info.php';
            include_once 'create_script.php';
            break;
        case 'logout':
            require 'connect_to_db.php';
            require 'profile_info.php';
            include_once 'logout.php';
            break;
        case 'imgresizeextravaganza':
            include_once 'imgresizeextravaganza.php';
            break;
        default:
            include_once 'view/404.php';
            break;
    }
    echo '</main>';
    include_once 'view/footer.php';
    ?>
    <script src="static/main.js"></script>
</body>
</html>