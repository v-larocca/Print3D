<?php

require_once 'config.php';
require_once 'autoloader.php';


$frontController = new CFrontController();
$frontController->run($_SERVER['REQUEST_URI']);
