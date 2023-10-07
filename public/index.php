<?php

mb_internal_encoding("UTF-8");

require_once(dirname(__DIR__) . "/vendor/autoload.php");

use Application\Controllers\Home\Home;
use Application\Lib\Path;

session_start();

try {
    if (isset($_GET['action']) && $_GET['action'] !== ''){
        $class = "Application\\Controllers\\" . mb_strtoupper(mb_substr($_GET['action'], 0, 1) . mb_substr($_GET['action'], 1));

        $classFound = false;

        if (class_exists('\\' . $class)) {
            (new $class())->execute();
            $classFound = true;
        }

        if (! $classFound) {
            throw new Exception("La page que vous demandez n'existe pas.");
        }
    } else {
        (new Home())->execute();
    }
} catch (Exception $exception) {
    $errorMessage = $exception->getMessage();
    var_dump($errorMessage);
    exit;
    require('templates/error.php');
}


