<?php
if($user_data){
    $_SESSION['user'] = null;
}
else{
    header('Location: ../sec-project');
}