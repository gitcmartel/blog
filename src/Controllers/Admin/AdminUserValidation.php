<?php

namespace Application\Controllers\Admin;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Models\DatabaseConnexion;
use Application\Lib\Session;

class AdminUserValidation 
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        $userFunction = "";
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository(new DatabaseConnexion);
            $activeUser = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $activeUser->userFunction;
            if($activeUser->isCreator()  && $activeUser->isValid){
                if(isset($_POST['userValidation']) && isset($_POST['devalidate'])){
                    //Updates the status user field
                    switch(gettype($_POST['userValidation'])){
                        case "array":
                            foreach($_POST['userValidation'] as $userId){
                                $user = $userRepository->getUser($userId);
                                if($_POST['devalidate'] === 'true'){
                                    if($user->isValid){
                                        $userRepository->setValidation($userId, 0);
                                    }
                                } else if($_POST['devalidate'] === 'false') {
                                    if(! $user->isValid){
                                        $userRepository->setValidation($userId, -1);
                                    }
                                }
                            }
                            break;
                        case "string" :
                            $user = $postRepository->getUser($_POST['userValidation']);
                            if($_POST['devalidate'] === 'true'){
                                if($user->isValid){
                                    $postRepository->setValidation($_POST['userValidation'], 0);
                                }
                            } else if($_POST['devalidate'] === 'false'){
                                if($user->isValid){
                                    $postRepository->setValidation($_POST['userValidation'], -1);
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
                
                echo $twig->render('adminUserList.twig', [ 
                    'actualPage' => "1", 
                    'totalPages' => $totalPages, 
                    'users' => $users, 
                    'userFunction' => $userFunction,
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
            'userFunction' => $userFunction,
            'activeUser' => Session::getActiveUser()
        ]);
    }
}