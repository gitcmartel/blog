<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigWarning;

class AdminUserDeletion
{
    #region Function
    public function execute()
    {
        #region Variables

        $userRepository = new UserRepository(new DatabaseConnexion);

        #endregion

        #region Conditions tests
        
        if(! UserActiveCheckValidity::check(array('Administrateur')) || ! isset($_GET['userId']) || trim($_GET['userId']) === ""){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return; 
        }

        #endregion


        #region Function execution
                    
        $user = $userRepository->getUser(trim($_GET['userId']));

        if (! $userRepository->deleteUser($user->getId())) {
            TwigWarning::display(
                "Un problème est survenu lors de la suppression de l'utilisateur.", 
                "index.php?action=Home\Home", 
                "Retour à l'accueil");
            return; 
        } else  {
            //We display the updated user list
            header("Location:index.php?action=Admin\User\AdminUserList&pageNumber=1");
            return;
        }

        #endregion
    }
    #endregion
}