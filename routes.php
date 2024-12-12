<?php
$controller = 'index';

if (isset($_SERVER['PATH_INFO'])) {
    $controller = str_replace('/', '', $_SERVER['PATH_INFO']);
}


if (!file_exists("controllers/{$controller}Controller.php")) {
    echo 'Endereço não encontrado';
    die();
}

require "controllers/{$controller}Controller.php";