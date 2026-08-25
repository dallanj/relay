<?php
require_once __DIR__ . '/../config.php';
$db = app_config()['db'];

$conn = new mysqli($db['host'], $db['username'], $db['password'], $db['database']);

session_start();
