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

        $userRepository = new UserRepository(new DatabaseConnexion);
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        if(! UserActiveCheckValidity::check(array('Administrateur')) || ! isset($_GET['userId'])){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return; 
        }

        $user = $userRepository->getUser($_GET['userId']);

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