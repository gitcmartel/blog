<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminUserModification
{
    #region Functions
    public function execute()
    {
        #region Variables

        $userRepository = new UserRepository();
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        $userId = filter_input(INPUT_GET, 'userId', FILTER_SANITIZE_NUMBER_INT);

        if(! UserActiveCheckValidity::check(array('Administrateur'))){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return; 
        }

        $user = $userRepository->getUser($userId);

        //Check if the userId exists in the database
        if($user->getId() === null){
            TwigWarning::display(
                "Un problème est survenu lors de l'affichage de l'utilisateur.", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;  
        }
        
        #endregion

        #region Function execution

        echo $twig->render('Admin\User\AdminUser.html.twig', [  
            'user' => $user, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}