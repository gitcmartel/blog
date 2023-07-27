<?php

mb_internal_encoding("UTF-8");

require_once(__DIR__ . "/vendor/autoload.php");

use Application\Controllers\Home;


try {
    if (isset($_GET['action']) && $_GET['action'] !== ''){
        $classes = array(
            "Application\\Controllers\\" . $_GET['action'], 
            "Application\\Controllers\\Api\\V1\\" . $_GET['action'] 
        );
        $classFound = false;
        foreach($classes as $class) {
            if (class_exists($class)) {
                (new $class())->execute();
                $classFound = true;
            }
        }
        if (! $classFound) {
            throw new Exception("La page que vous demandez n'existe pas.");
        }
    } else {
        (new Home())->execute();
    }
} catch (Execption $exception) {
    $errorMessage = $exception->getMessage();

    require('templates/error.php');
}


