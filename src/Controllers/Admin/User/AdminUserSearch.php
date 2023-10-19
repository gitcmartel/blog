<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminUserSearch
{
    #region Functions
    public function execute()
    {
        #region Variables

        $userRepository = new UserRepository(new DatabaseConnexion);
        $user = "";
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        if(! UserActiveCheckValidity::check(array('Administrateur')) || ! isset($_POST['searchString'])){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php/action=Home\Home", 
                "Nous contacter");
            return; 
        }

        if(trim($_POST['searchString']) === ""){
            return;
        }

        #endregion

        #region Function execution
        
        $users = $userRepository->searchUsers(trim($_POST['searchString']));

        echo $twig->render('Admin\User\AdminUserList.html.twig', [ 
            'actualPage' => "1", 
            'totalPages' => "1", 
            'users' => $users, 
            'userFunction' => Session::getActiveUserFunction(),
            'activeUser' => Session::getActiveUser()
        ]);

        #endregion
    }
    #endregion
}