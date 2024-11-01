<?php
$config = parse_ini_file('ini/configM.ini');
$mysql = new PDO("mysql:host=".$config['host'].";dbname=".$config['database'], $config['name'], $config['password']);

$configS = parse_ini_file('ini/configS.ini');
// due to frederic dot glorieux at diple dot net's commentary to PDO
// sqlite on php.net 13 years ago, disabling limitations to memory
$sqlite = new PDO('sqlite::memory:', $configS['name'], $configS['password']);