<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit', '12M');

define ('_PROJECT_LOCATION', dirname(__FILE__));
define ('_PROJECT_LIB', _PROJECT_LOCATION. "/lib");

// Config 
define ('DB_HOST', 'localhost');
define ('DB_NAME', 'test');
define ('DB_USER', 'root');
define ('DB_PASS', '');

ini_set('include_path', _PROJECT_LIB);


session_set_cookie_params(0, '/', null, false, true);
session_start();