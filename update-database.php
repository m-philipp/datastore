<?php
include_once './lib/epiphany/Epi.php';
Epi::setPath('base', './lib/epiphany');

Epi::init('database', 'config');

$config = './';
EPi::setPath("config", $config);
getConfig()->load('config.ini');

$sql_conf = getConfig()->get('mysql');


EpiDatabase::employ('mysql', $sql_conf->database,
	$sql_conf->server,
	$sql_conf->user,
	$sql_conf->password);

$update_sql = file_get_contents("update.sql");

getDatabase()->execute($update_sql);


?>
DONE!
