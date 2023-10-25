<?php

namespace Application\Controllers\Admin\Comment;

use Application\Lib\UserActiveCheckValidity;
use Application\Models\CommentRepository;
use Application\Models\Post;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminCommentModification
{
    #region Functions
    public function execute()
    {

        #region variables
        $commentRepository = new CommentRepository();
        $comment = "";
        $post = new Post();

        #endregion

        #region Conditions tests

        //If the active user is not an admin
        if(! UserActiveCheckValidity::check(array('Administrateur'))){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;
        }

        //If the commentId variable is not set
        if (! isset($_GET['commentId']) || trim($_GET['commentId']) === ""){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        #endregion

        #region Function execution
        $comment = $commentRepository->getComment($_GET['commentId']);

        $twig = TwigLoader::getEnvironment();

        echo $twig->render('Admin\Comment\AdminComment.html.twig', [  
            'commentId' => $comment->getId(),
            'postId' => $comment->getPost()->getId(),  
            'commentString' => $comment->getComment(),
            'publicationDate' => $comment->getPublicationDate(),
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}