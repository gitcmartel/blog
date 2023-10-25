<?php

namespace Application\Controllers\Admin\User;

use Application\Models\User;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminUserCreation
{
    #region Functions
    public function execute()
    {
        #region Variables

        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Condtions tests

        if(! UserActiveCheckValidity::check(array('Administrateur'))){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return; 
        }

        #endregion
        
        #region Function execution

        $user = new User();

        echo $twig->render('Admin\User\AdminUser.html.twig', [ 
            'user' => $user, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}