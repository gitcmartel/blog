<?php

namespace Application\Controllers\Comment;

use Application\Lib\Session;
use Application\Models\CommentRepository;
use Application\Lib\TwigWarning;

class CommentDeletion
{
    //region Functions
    /**
     * Controller main function
     */
    public function execute(): void
    {

        //region Variables

        $commentRepository = new CommentRepository();
        $comment = "";

        //endregion

        //region Conditions tests

        //We display an warning message if one of the following conditions are false
        $commentId = filter_input(INPUT_GET, 'commentId', FILTER_SANITIZE_NUMBER_INT);

        //If the commentId variable is not set
        if ($commentId === false || $commentId === null) {
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.",
                "index.php?action=Home\Home",
                "Retour à la page d'accueil");
            return;
        }

        $comment = $commentRepository->getComment($commentId);

        //If the active user is not the comment's author
        if (Session::getActiveUserId() !== $comment->getUser()->getId()) {
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour effectuer cette action. Contactez l'administrateur du site",
                "index.php?action=Home\Home",
                "Nous contacter");
            return;
        }

        //If the comment id is null
        if ($comment->getid() === null) {
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.",
                "index.php?action=Home\Home",
                "Retour à la page d'accueil");
            return;
        }

        //endregion

        //region Function execution

        $commentRepository->deleteComment($comment);

        //We display the updated comment list
        header('Location:index.php?action=Post\PostDisplay&postId=' . $comment->getPost()->getId() . '&alert=true&alertType=Deletion');
        return;

        //endregion
    }
    //endregion
}
//end execute()
