<?php

$global_config = dirname(dirname(__DIR__)) . '/data/config.php';

if (file_exists($global_config)) {
    require $global_config;
    $db_hosts = explode(':', $db_host);
    $db_host = isset($db_hosts[0]) ? $db_hosts[0] : 'localhost'; // str_replace('localhost', '127.0.0.1', $db_hosts[0]);
    $db_port = isset($db_hosts[1]) ? $db_hosts[1] : '3306';
    return [
        'db_type' => 'mysql',
        'db_host' => $db_host,
        'db_user' => $db_user,
        'db_pwd' => $db_pass,
        'db_name' => $db_name,
        'db_prefix' => $prefix,
        'db_port' => $db_port,
        'db_charset' => 'utf8',
    ];
}

die('Unable to load the database configuration file.');
