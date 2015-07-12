<?php

$sid = 1;

// Load Epiphany
include_once "./lib/epiphany/Epi.php";


$config = './';

EPi::setPath("config", $config);
Epi::setPath("base", "./lib/epiphany");
Epi::setPath("view", "./view");

// Initialize all Epi modules that are used
Epi::init("route", "template", "api", "database", "config");
getConfig()->load('config.ini');

// Connect to database
$sql_conf = getConfig()->get('mysql');

EpiDatabase::employ('mysql', $sql_conf->database,
    $sql_conf->server,
    $sql_conf->user,
    $sql_conf->password);


for ($i = 0; $i < 100; $i++) {
    time_nanosleep(0, 100000000);

    $time = microtime(true) * 1000;
    $value = sin((pi() * $i * 2) / 100);
    print($time . " " . $value . "\n");

    $userId = getDatabase()->execute('INSERT INTO store(sid, val, loggedTime) VALUES(:sid, :val, :loggedTime)', array(':sid' => $sid, ':loggedTime' => $time, ':val' => $value));

    if ($i == 100) {
        $i = 0;
    }
}

?>
