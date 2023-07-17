<?php 

namespace Application\Controllers;
include 'vendor/autoload.php';


class Home 
{
    public function execute() {
        
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        
        echo $twig->render('header.twig', ['title' => "Explorez l'univers du développement web avec Christophe Martel : 
        des lignes de code qui transforment vos idées en réalité numérique."]);
    }
}