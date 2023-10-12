<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Models\UserActiveCheckValidity;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;
use Application\Lib\TwigLoader;

class AdminUserSearch
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";

        if(UserActiveCheckValidity::check(array('Administrateur'))){
            if(isset($_POST['searchString'])){
                if(trim($_POST['searchString']) !== ""){
                    $userRepository = new UserRepository(new DatabaseConnexion);
                    $users = $userRepository->searchUsers(trim($_POST['searchString']));

                    $twig = TwigLoader::getEnvironment();
                    
                    echo $twig->render('Admin\User\AdminUserList.html.twig', [ 
                        'actualPage' => "1", 
                        'totalPages' => "1", 
                        'users' => $users, 
                        'userFunction' => Session::getActiveUserFunction(),
                        'activeUser' => Session::getActiveUser()
                    ]);
                    return;
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