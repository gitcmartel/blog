<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;
use Application\Lib\TwigLoader;

class AdminUserList 
{
    #region Functions
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";

        if(UserActiveCheckValidity::check(array('Administrateur'))){
            $userRepository = new UserRepository(new DatabaseConnexion);
            $totalPages = $userRepository->getTotalPageNumber(10);;
            $pageNumber = 1;
            if (isset($_GET['pageNumber'])){
                if($_GET['pageNumber'] !== 0){
                    $users = $userRepository->getUsers($_GET['pageNumber'], 10);
                    $pageNumber = $_GET['pageNumber'];
                }
            } else {
                $users = $postRepository->getUsers(1, 10);
            }
            
            $twig = TwigLoader::getEnvironment();
            
            echo $twig->render('Admin\User\AdminUserList.html.twig', [ 
                'actualPage' => $pageNumber, 
                'totalPages' => $totalPages, 
                'users' => $users, 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => Session::getActiveUserFunction()
            ]);
            return;
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
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);
    }
    #endregion
}