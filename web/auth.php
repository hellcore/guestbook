<?php
ob_start();
require_once '../bootstrap.php';
require_once 'Controller/Auth.php';

$controller = new Controller_Auth;
$controller->run();
ob_end_flush();