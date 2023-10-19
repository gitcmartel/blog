<?php

namespace Application\Controllers\Admin\Comment;

use Application\Lib\UserActiveCheckValidity;
use Application\Models\Comment;
use Application\Models\CommentRepository;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminCommentList
{
    #region Functions
    public function execute()
    {

        #region Variables

        $commentRepository = new CommentRepository();
        $comments = "";
        $pageNumber = 1;
        $totalPages = 1;

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

        //If the pageNumber variable is not set
        if (! isset($_GET['pageNumber'])){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        //If the pageNumber variable is empty
        if(trim($_GET['pageNumber']) === ""){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        #endregion

        #region Function execution
        $totalPages = $commentRepository->getTotalPageNumber(10);

        if($_GET['pageNumber'] !== 0){
            $comments = $commentRepository->getComments($_GET['pageNumber'], 10);
            $pageNumber = $_GET['pageNumber'];
        }else {
            $comments = $commentRepository->getComments(1, 10);
        } 

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Admin\Comment\AdminCommentList.html.twig', [ 
            'actualPage' => $pageNumber, 
            'totalPages' => $totalPages, 
            'comments' => $comments, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}