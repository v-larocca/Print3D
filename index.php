<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';
require_once 'autoloader.php';


$frontController = new CFrontController();
$frontController->run($_SERVER['REQUEST_URI']);
