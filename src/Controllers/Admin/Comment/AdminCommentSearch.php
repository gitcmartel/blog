<?php

namespace Application\Controllers\Admin\Comment;

use Application\Lib\UserActiveCheckValidity;
use Application\Models\Comment;
use Application\Models\CommentRepository;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminCommentSearch
{
    #region Functions
    public function execute()
    {
        #region variables
        $commentRepository = new CommentRepository();
        $comments = "";
        $twig = TwigLoader::getEnvironment();
        #endregion

        #region Conditions tests

        //If the active user is not an admin
        if(! UserActiveCheckValidity::check(array('Administrateur'))){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php/action=Home\Home", 
                "Nous contacter");
            return;
        }

        //If the searchString variable is not set
        if (! isset($_POST['searchString'])){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php/action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        //If the searchString variable is empty
        if (trim($_POST['searchString']) === ""){
            return;
        }

        #endregion

        #region Function execution

        $comments = $commentRepository->searchComments(trim($_POST['searchString']));

        echo $twig->render('Admin\Comment\AdminCommentList.html.twig', [ 
            'actualPage' => "1", 
            'totalPages' => "1", 
            'comments' => $comments, 
            'userFunction' => Session::getActiveUserFunction(),
            'activeUser' => Session::getActiveUser()
        ]);

        #endregion
    }
    #endregion
}