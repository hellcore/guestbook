<?php
ob_start();
require_once '../bootstrap.php';
require_once 'Controller/Guestbook.php';

$controller = new Controller_Guestbook;
$controller->run();
ob_end_flush();