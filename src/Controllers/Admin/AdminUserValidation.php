<?php

namespace Application\Controllers\Admin;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;

class AdminUserValidation 
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository();
            $user = $userRepository->getUser($_SESSION['userId']);

            if($user->isCreator()  && $user->isValid === -1){
                if(isset($_POST['userValidation']) && isset($_POST['devalidate'])){
                    //Updates the status user field
                    switch(gettype($_POST['userValidation'])){
                        case "array":
                            foreach($_POST['userValidation'] as $userId){
                                $user = $userRepository->getUser($userId);
                                if($_POST['devalidate'] === "true"){
                                    if($user->isValid === -1){
                                        $userRepository->devalidate($userId);
                                    }
                                } else if($_POST['devalidate'] === "false") {
                                    if($user->isValid === 0){
                                        $userRepository->validate($userId);
                                    }
                                }
                            }
                            break;
                        case "string" :
                            $user = $postRepository->getUser($_POST['userValidation']);
                            if($_POST['devalidate'] === "true"){
                                if($user->isValid === -1){
                                    $postRepository->devalidate($_POST['userValidation']);
                                }
                            } else {
                                if($user->isValid === 0){
                                    $postRepository->validate($_POST['userValidation']);
                                }
                            }
                            break;
                    }
                }

                $totalPages = $userRepository->getTotalPageNumber(10);;
                if (isset($_GET['pageNumber'])){
                    if($_GET['pageNumber'] !== 0){
                        $users = $userRepository->getUsers($_GET['pageNumber'], 10);
                    }
                } else {
                    $users = $userRepository->getUsers(1, 10);
                }
                
                $loader = new \Twig\Loader\FilesystemLoader('templates');
                $twig = new \Twig\Environment($loader, ['cache' => false]);
                
                echo $twig->render('adminPostList.twig', [ 
                    'actualPage' => "1", 
                    'totalPages' => $totalPages, 
                    'users' => $users, 
                    'activeUser' => Session::getActiveUser()
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
            'activeUser' => Session::getActiveUser()
        ]);
    }
}