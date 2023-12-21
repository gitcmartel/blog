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
    /**
     * Controller main function
     */
    public function execute(): void
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

        $postId = filter_input(INPUT_GET, 'postId', FILTER_SANITIZE_NUMBER_INT);

        //If the postId variable is not set
        if ($postId === false || $postId === null){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        #endregion

        #region Function execution

        $postRepository = new PostRepository();
        $post = $postRepository->getPost($postId);
        $comment = new Comment();

        $twig = TwigLoader::getEnvironment();

        echo $twig->render(
            'Comment\Comment.html.twig', [  
            'comment'      => $comment, 
            'post'         => $post, 
            'activeUser'   => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);
        
        #endregion
    }  
    #endregion   
}
//End execute()
