<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;
use Application\Lib\Constants;

class AdminUserList 
{
    #region Functions
    public function execute()
    {
        #region Variables

        $userRepository = new UserRepository();
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

        if (isset($_GET['pageNumber']) && $_GET['pageNumber'] !== 0){
            $pageNumber = $_GET['pageNumber'];

        }

        #endregion

        #region Function execution
            
        $totalPages = $userRepository->getTotalPageNumber(Constants::NUMBER_OF_USERS_PER_PAGE);;

        $users = $userRepository->getUsers($pageNumber, Constants::NUMBER_OF_POSTS_PER_PAGE);

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