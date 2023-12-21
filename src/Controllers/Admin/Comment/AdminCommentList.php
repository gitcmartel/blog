<?php

namespace Application\Controllers\Admin\Comment;

use Application\Lib\UserActiveCheckValidity;
use Application\Models\CommentRepository;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;
use Application\Lib\Constants;
use Application\Lib\Alert;

class AdminCommentList
{
    #region Functions
    /**
     * Controller main function
     */
    public function execute() : void
    {

        #region Variables

        $commentRepository = new CommentRepository();
        $comments = "";
        $pageNumber = 1;
        $totalPages = 1;

        #endregion

        #region Conditions tests

        //If the active user is not an admin
        if(UserActiveCheckValidity::check(array('Administrateur')) === false){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;
        }

        $alert = filter_input(INPUT_GET, 'alert', FILTER_SANITIZE_SPECIAL_CHARS);
        $alertType = filter_input(INPUT_GET, 'alertType', FILTER_SANITIZE_SPECIAL_CHARS);
        $pageNumber = filter_input(INPUT_GET, 'pageNumber', FILTER_SANITIZE_NUMBER_INT);

        //If the pageNumber variable is not set
        if ($pageNumber === false || $pageNumber === null){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        #endregion

        #region Function execution
        $totalPages = $commentRepository->getTotalPageNumber(Constants::NUMBER_OF_COMMENTS_PER_PAGE);

        if($pageNumber !== 0){
            $comments = $commentRepository->getComments($pageNumber, Constants::NUMBER_OF_COMMENTS_PER_PAGE);
        }else {
            $comments = $commentRepository->getComments(1, Constants::NUMBER_OF_COMMENTS_PER_PAGE);
        } 

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render(
            'Admin\Comment\AdminCommentList.html.twig', [ 
            'actualPage'   => $pageNumber, 
            'totalPages'   => $totalPages, 
            'comments'     => $comments, 
            'alert'        => ($alert !== false && $alert !== null) ? $alert : '',
            'alertMessage' => ($alertType !== false && $alertType !== null) ? Alert::getMessage($alertType) : '',
            'activeUser'   => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}
//end execute()
