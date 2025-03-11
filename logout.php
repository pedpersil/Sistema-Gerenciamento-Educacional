<?php
require_once __DIR__ . '/classes/Auth.php';

session_start();

$auth = new Auth();
$auth->logout();

?>
