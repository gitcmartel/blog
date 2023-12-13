<?php

mb_internal_encoding("UTF-8");

require_once("../vendor/autoload.php");

use Application\Controllers\Home\Home;
use Application\Lib\TwigWarning;

session_start();

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_URL);

try {
    if (isset($action) && $action !== ''){
        $class = "Application\\Controllers\\" .
        preg_replace_callback(
            '#(?<=\\\\)[a-z]#',
            function (array $match): string {
                return mb_strtoupper($match[0]);
            },
            $action
        );

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
    TwigWarning::display(
        'L\erreur suivante c\'est produite : ' . $errorMessage, 
        'index.php?action=Home\Home', 
        "Nous contacter"
    );
}


