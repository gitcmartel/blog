<?php

namespace Application\Controllers\Admin\Comment;

use Application\Lib\UserActiveCheckValidity;
use Application\Models\CommentRepository;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminCommentModification
{
    //region Functions
    /**
     * Controller main function
     */
    public function execute() : void
    {

        //region variables
        $commentRepository = new CommentRepository();
        $comment = "";

        //endregion

        //region Conditions tests

        //If the active user is not an admin
        if(UserActiveCheckValidity::check(array('Administrateur')) === false){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;
        }

        $commentId = filter_input(INPUT_GET, 'commentId', FILTER_SANITIZE_NUMBER_INT);

        //If the commentId variable is not set
        if ($commentId === false || $commentId === null){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        $comment = $commentRepository->getComment($commentId);

        //Check if the comment is present in the database
        if($comment->getid() === null){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement du commentaire.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        //endregion

        //region Function execution
        

        $twig = TwigLoader::getEnvironment();

        echo $twig->render(
            'Admin\Comment\AdminComment.html.twig', [  
            'comment'      => $comment,
            'post'         => $comment->getPost(),  
            'activeUser'   => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        //endregion
    }
    //endregion
}
//end execute()
