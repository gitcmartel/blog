<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Models\UserActiveCheckValidity;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;
use Application\Lib\TwigLoader;

class AdminUserDeletion
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";

        if(UserActiveCheckValidity::check(array('Administrateur'))){
            if (isset($_GET['userId'])){
                if(trim($_GET['userId']) !== ""){
                    $userRepository = new UserRepository(new DatabaseConnexion);
                    $user = $userRepository->getUser(trim($_GET['userId']));
                    if(isset($user->id)){
                        if (! $userRepository->deleteUser($user->id)) {
                            $warningGeneral = "Un problème est survenu lors de la suppression de l'utilisateur";
                            $warningLink = "index.php?action=Admin\User\AdminUserList&pageNumber=1";
                            $warningLinkMessage = "Retour à la liste des utilisateurs";
                        } else  {
                            //We display the updated user list
                            header("Location:index.php?action=Admin\User\AdminUserList&pageNumber=1");
                            return;
                        }
                    }
                }
            }
        } else {
            $warningGeneral = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
            $warningLink = "index.php/action=Home\Home";
            $warningLinkMessage = "Nous contacter";
        }

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Warning\NotAllowed.html.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'userFunction' => Session::getActiveUserFunction(),
            'activeUser' => Session::getActiveUser()
        ]);
    }
}