<?php

namespace Application\Controllers\Admin;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;

class AdminUserDeletion
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        $userFunction = "";
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository();
            $activeUser = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $activeUser->userFunction;
            if($activeUser->isCreator()  && $activeUser->isValid){
                if (isset($_GET['userId'])){
                    if(trim($_GET['userId']) !== ""){
                        $user = $userRepository->getUser(trim($_GET['userId']));
                        if(isset($user->id)){
                            if (! $userRepository->deleteUser($user->id)) {
                                $warningGeneral = "Un problème est survenu lors de la suppression de l'utilisateur";
                                $warningLink = "index.php?action=AdminUserList&pageNumber=1";
                                $warningLinkMessage = "Retour à la liste des utilisateurs";
                            } else  {
                                //We display the updated user list
                                header("Location:index.php?action=AdminUserList&pageNumber=1");
                                return;
                            }
                        }
                    }
                }
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