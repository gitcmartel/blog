<?php

mb_internal_encoding("UTF-8");

require_once(__DIR__ . "/vendor/autoload.php");

use Application\Controllers\Home;

try {
    if (isset($_GET['action']) && $_GET['action'] !== ''){
        $class = "Application\\Controllers\\" . $_GET['action'];
        if (class_exists($class)) {
            (new $class())->execute();
        } else {
            throw new Exception("La page que vous demandez n'existe pas.");
        }
    } else {
        (new Home())->execute();
    }
} catch (Execption $exception) {
    $erreorMessage = $exception->getMessage();

    require('templates/error.php');
}


