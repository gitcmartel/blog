<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminPost
{
    #region Functions
    public function execute() 
    {
        #region Variables
        $post = "";
        $postRepository = new PostRepository(new DatabaseConnexion);

        #endregion

        #region Conditions tests

        if(! UserActiveCheckValidity::check(array('Administrateur', 'Createur'))){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;     
        }

        #endregion

        #region Function execution
        if(isset($_GET['postId'])){
            $post = $postRepository->getPost($_GET['postId']);
        }
        
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