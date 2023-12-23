<?php

namespace Application\Controllers\Admin\Comment;

use Application\Lib\UserActiveCheckValidity;
use Application\Models\CommentRepository;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminCommentSearch
{
    //region Functions
    /**
     * Controller main function
     */
    public function execute() : void
    {
        //region variables
        $commentRepository = new CommentRepository();
        $comments = "";
        $twig = TwigLoader::getEnvironment();
        //endregion

        //region Conditions tests

        //If the active user is not an admin
        if (UserActiveCheckValidity::check(['Administrateur']) === false) {
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site",
                "index.php?action=Home\Home",
                "Nous contacter");
            return;
        }

        $searchString = filter_input(INPUT_POST, 'searchString', FILTER_SANITIZE_SPECIAL_CHARS);

        //If the searchString variable is not set
        if ($searchString === false || $searchString === null) {
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.",
                "index.php?action=Home\Home",
                "Retour à la page d'accueil");
            return;
        }

        //endregion

        //region Function execution

        $comments = $commentRepository->searchComments(trim($searchString));

        echo $twig->render(
            'Admin\Comment\AdminCommentList.html.twig', [
            'actualPage'      => "1",
            'totalPages'      => "1",
            'comments'        => $comments,
            'searchString'    => $searchString,
            'userFunction'    => Session::getActiveUserFunction(),
            'activeUser'      => Session::getActiveUser()
        ]);

        //endregion
    }
    //endregion
}
//end execute()
