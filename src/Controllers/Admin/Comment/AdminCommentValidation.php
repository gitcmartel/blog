<?php

namespace Application\Controllers\Admin\Comment;

use Application\Lib\UserActiveCheckValidity;
use Application\Models\CommentRepository;
use Application\Models\Comment;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminCommentValidation
{
    #region Functions
    public function execute()
    {
        #region Variables

        $commentRepository = new CommentRepository();
        $comment = "";
        $totalPages = $commentRepository->getTotalPageNumber(10);
        $twig = TwigLoader::getEnvironment();
        
        #endregion

        #region Conditions tests

        if (!UserActiveCheckValidity::check(['Administrateur']) || !isset($_POST['commentValidation']) ||
            !isset($_POST['devalidate'])) {
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.",
                "index.php/action=Home\Home",
                "Retour à la page d'accueil"
            );
            return;
        }
    
        if (isset($_GET['pageNumber'])){
            if($_GET['pageNumber'] !== 0){
                $comments = $commentRepository->getComments($_GET['pageNumber'], 10);
            }
        } else {
            $comments = $commentRepository->getComments(1, 10);
        }

        #endregion

        #region Function execution
    
        //Updates the comment publicationDate field
        $commentIds = is_array($_POST['commentValidation']) ? $_POST['commentValidation'] : [$_POST['commentValidation']];

        foreach ($commentIds as $commentId) {
            $comment = $commentRepository->getComment($commentId);
        
            if ($_POST['devalidate'] === 'true') {
                $commentRepository->setPublicationDateToNull($commentId);
            } elseif ($_POST['devalidate'] === 'false') {
                $commentRepository->setPublicationDate($commentId);
            }
        }

        echo $twig->render('Admin\Comment\AdminCommentList.html.twig', [ 
            'actualPage' => "1", 
            'totalPages' => $totalPages, 
            'comments' => $comments, 
            'userFunction' => Session::getActiveUserFunction(),
            'activeUser' => Session::getActiveUser()
        ]);

        #endregion
    }
    #endregion
}