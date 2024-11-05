<?php
session_destroy();
$_SESSION['response'] = [0, 'Выход произошёл успешно'];
header('Location: ../'.$dir);