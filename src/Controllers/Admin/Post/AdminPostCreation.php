<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\Post;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminPostCreation
{
    #region Functions
    /**
     * Controller main function
     */
    public function execute() : void
    {

        #region Conditions tests

        if (UserActiveCheckValidity::check(array('Administrateur', 'Createur')) === false) {
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site",
                "index.php?action=Home\Home",
                "Nous contacter");
            return;
        }

        #endregion

        #region Function execution
        $post = new Post();

        $twig = TwigLoader::getEnvironment();

        echo $twig->render('Admin\Post\AdminPost.html.twig', [
            'post' => $post,
            'activeUser' => Session::getActiveUser(),
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}
//end execute()
