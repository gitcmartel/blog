<?php

namespace Application\Controllers\Admin\Comment;

use Application\Lib\UserActiveCheckValidity;
use Application\Models\CommentRepository;
use Application\Lib\TwigWarning;

class AdminCommentDeletion
{
    #region Functions
    public function execute()
    {

        #region Variables
        
        $commentRepository = new CommentRepository();
        $comment = "";

        #endregion

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

        //If the commentId variable is not set
        if (! isset($_GET['commentId']) || trim($_GET['commentId']) === ""){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        $comment = $commentRepository->getComment(trim($_GET['commentId']));

        //If the comment id is null
        if($comment->getid() === null){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        #endregion

        #region Function execution

        $commentRepository->deleteComment($comment);

        //We display the updated comment list
        header('Location:index.php?action=Admin\Comment\AdminCommentList&pageNumber=1');
        return;

        #endregion
    }
    #endregion
}