<?php

namespace Application\Controllers\Admin\Comment;

use Application\Models\PostRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Models\Comment;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminCommentCreation
{
    #region Functions
    public function execute()
    {
        #region Conditions tests
        
        //We display an warning message if one of the following conditions are false

        //If the active user is not an admin
        if(! UserActiveCheckValidity::check(array('Administrateur'))){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;
        }

        //If the postId variable is not set
        if (! isset($_GET['postId']) || trim($_GET['postId']) === ""){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        #endregion

        #region Function execution

        $postRepository = new PostRepository();
        $post = $postRepository->getPost($_GET['postId']);
        $comment = new Comment();

        $twig = TwigLoader::getEnvironment();

        echo $twig->render('Admin\Comment\AdminComment.html.twig', [  
            'comment' => $comment, 
            'post' => $post, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);
        
        #endregion
    }  
    #endregion   
}