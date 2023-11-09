<?php

namespace Application\Controllers\Comment;

use Application\Models\PostRepository;
use Application\Models\Comment;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class CommentCreation
{
    #region Functions
    public function execute()
    {
        #region Conditions tests
        
        //We display an warning message if one of the following conditions are false

        //If the user is not logged in
        if(Session::getActiveUser() === ""){
            TwigWarning::display(
                "Vous devez vous connecter pour pouvoir ajouter un commentaire.", 
                "index.php?action=Connexion\Connexion", 
                "Se connecter");
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

        echo $twig->render('Comment\Comment.html.twig', [  
            'comment' => $comment, 
            'post' => $post, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);
        
        #endregion
    }  
    #endregion   
}