<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminUserList 
{
    #region Functions
    public function execute()
    {
        #region Variables

        $userRepository = new UserRepository(new DatabaseConnexion);
        $totalPages = 1;
        $pageNumber = 1;
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests
        if(! UserActiveCheckValidity::check(array('Administrateur'))){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return; 
        }

        #endregion

        #region Function execution
            
        $totalPages = $userRepository->getTotalPageNumber(10);;

        if (isset($_GET['pageNumber'])){
            if($_GET['pageNumber'] !== 0){
                $users = $userRepository->getUsers($_GET['pageNumber'], 10);
                $pageNumber = $_GET['pageNumber'];
            }
        } else {
            $users = $postRepository->getUsers(1, 10);
        }
        
        echo $twig->render('Admin\User\AdminUserList.html.twig', [ 
            'actualPage' => $pageNumber, 
            'totalPages' => $totalPages, 
            'users' => $users, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}