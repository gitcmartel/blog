<?php

namespace Application\Controllers\Comment;

use Application\Models\CommentRepository;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class CommentModification
{
    #region Functions
    public function execute()
    {

        #region variables
        $commentRepository = new CommentRepository();
        $comment = "";

        #endregion

        #region Conditions tests

        //If the commentId variable is not set
        if (! isset($_GET['commentId']) || trim($_GET['commentId']) === ""){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        $comment = $commentRepository->getComment($_GET['commentId'] === '' ? null : $_GET['commentId']);

        //If the active user is not the comment's author
        if(Session::getActiveUserId() !== $comment->getUser()->getId()){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;
        }

        //Check if the comment is present in the database
        if($comment->getid() === null){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement du commentaire.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        #endregion

        #region Function execution
        

        $twig = TwigLoader::getEnvironment();

        echo $twig->render('Comment\Comment.html.twig', [  
            'comment' => $comment,
            'post' => $comment->getPost(),  
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}