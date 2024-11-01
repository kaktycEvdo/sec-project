<?php
$stmt = $mysql->query("SELECT id, email, password, created_at, pfp, status FROM users WHERE (id = '".$_SESSION['user']."')");

$user_info = $stmt->fetch(PDO::FETCH_ASSOC);