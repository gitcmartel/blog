<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\Post;
use Application\Models\PostRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminPostModification
{
    #region Function
    public function execute()
    {
        #region Variables

        $postRepository = new PostRepository(new DatabaseConnexion);
        $post = "";
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests
        
        if(! UserActiveCheckValidity::check(array('Administrateur', 'Createur')) || ! isset($_GET['postId'])){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;  
        }

        if(trim($_GET['postId']) === ""){
            TwigWarning::display(
                "Une erreur est survenue lors de la modification du post.", 
                "index.php?action=Home\Home", 
                "Retour à l'accueil");
            return;  
        }

        #endregion
  
        #region Function execution

        $post = $postRepository->getPost($_GET['postId']);

        echo $twig->render('Admin\Post\AdminPost.html.twig', [ 
            'post' => $post, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}