<?php

namespace Application\Controllers\Admin;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;

class AdminUserList 
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        $userFunction = "";
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository();
            $user = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $user->userFunction;
            if($user->isAdmin()){
                $totalPages = $userRepository->getTotalPageNumber(10);;
                if (isset($_GET['pageNumber'])){
                    if($_GET['pageNumber'] !== 0){
                        $users = $userRepository->getUsers($_GET['pageNumber'], 10);
                    }
                } else {
                    $users = $postRepository->getUsers(1, 10);
                }
                
                $loader = new \Twig\Loader\FilesystemLoader('templates');
                $twig = new \Twig\Environment($loader, ['cache' => false]);
                
                echo $twig->render('adminUserList.twig', [ 
                    'actualPage' => $_GET['pageNumber'], 
                    'totalPages' => $totalPages, 
                    'posts' => $posts, 
                    'activeUser' => Session::getActiveUser(), 
                    'userFunction' => $userFunction
                ]);
                return;
            } else {
                $warningGeneral = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
                $warningLink = "index.php/action=Home";
                $warningLinkMessage = "Nous contacter";
            }
        } else {
            $warningGeneral = "Veuillez-vous identifier pour pouvoir accéder à cette page";
            $warningLink = "index.php?action=Connexion";
            $warningLinkMessage = "Se connecter";
        }

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, ['cache' => false]);
        
        echo $twig->render('notAllowed.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => $userFunction
        ]);
    }
}