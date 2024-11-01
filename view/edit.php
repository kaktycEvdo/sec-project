<?php
if ($user_info){
    include_once 'edit_form.php';
}
else{
    header('Location: ../sec-project');
}