<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminPostModification
{
    #region Function
    public function execute()
    {
        #region Variables

        $postRepository = new PostRepository();
        $post = "";
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests
        
        if(! UserActiveCheckValidity::check(array('Administrateur', 'Createur')) || ! isset($_GET['postId']) 
        || trim($_GET['postId']) === ""){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;  
        }

        $post = $postRepository->getPost($_GET['postId']);

        //Check if the postId exists in the database
        if($post->getId() === null){
            TwigWarning::display(
                "Un problème est survenu lors de l'affichage du post.", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;  
        }

        #endregion
  
        #region Function execution

        echo $twig->render('Admin\Post\AdminPost.html.twig', [ 
            'post' => $post, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}