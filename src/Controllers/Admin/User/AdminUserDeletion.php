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

        $userRepository = new UserRepository();

        #endregion

        #region Conditions tests
        
        if(! UserActiveCheckValidity::check(array('Administrateur')) || ! isset($_GET['userId']) || trim($_GET['userId']) === ""){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return; 
        }

        $user = $userRepository->getUser(trim($_GET['userId']));

        //If the user id is null
        if($user->getid() === null){
            TwigWarning::display(
                "Une erreur est survenue lors de la suppression de l'utilisateur.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        #endregion


        #region Function execution
                    


        if (! $userRepository->deleteUser($user->getId())) {
            TwigWarning::display(
                "Un problème est survenu lors de la suppression de l'utilisateur.", 
                "index.php?action=Home\Home", 
                "Retour à l'accueil");
            return; 
        } else  {
            //We display the updated user list
            header("Location:index.php?action=Admin\User\AdminUserList&pageNumber=1&alert=true&alertType=Deletion");
            return;
        }

        #endregion
    }
    #endregion
}