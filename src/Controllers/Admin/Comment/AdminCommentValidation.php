<?php

namespace Application\Controllers\Admin\Comment;

use Application\Lib\UserActiveCheckValidity;
use Application\Models\CommentRepository;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminCommentValidation
{
    #region Functions
    public function execute()
    {
        #region Variables

        $commentRepository = new CommentRepository();
        $comments = "";
        $totalPages = $commentRepository->getTotalPageNumber(10);
        $twig = TwigLoader::getEnvironment();
        
        #endregion

        #region Conditions tests

        if (!UserActiveCheckValidity::check(['Administrateur']) || !isset($_POST['commentValidation'])) {
            TwigWarning::display(
                "Vous n'avez pas le droits requis pour accéder a cette page.",
                "index.php?action=Home\Home",
                "Retour à la page d'accueil"
            );
            return;
        }

        $pageNumber = filter_input(INPUT_GET, 'pageNumber', FILTER_SANITIZE_NUMBER_INT);
        $validation = filter_input(INPUT_POST, 'validation', FILTER_VALIDATE_BOOLEAN);
        
        if ($pageNumber === false || $pageNumber === null || $pageNumber === '0'){
            $pageNumber = 1;
        }

        $commentIds = is_array($_POST['commentValidation']) ? $_POST['commentValidation'] : [$_POST['commentValidation']];

        //Check if all the commentid's are present in the database and if the validation variable is present
        if(! $commentRepository->checkIds($commentIds, 'comment', 'id') || $validation === null){
            TwigWarning::display(
                "Une erreur est survenue lors de la validation du ou des commentaires.",
                "index.php?action=Admin\Comment\AdminCommentList&pageNumber=1",
                "Retour à la page des commentaires"
            );
            return;
        }

        $validation = boolval($validation);

        #endregion

        #region Function execution
    
        //Updates the comment publicationDate field
        
        foreach ($commentIds as $commentId) {
            if (! $validation) {
                $commentRepository->setPublicationDateToNull($commentId);
            } elseif ($validation) {
                $commentRepository->setPublicationDate($commentId);
            }
        }

        $comments = $commentRepository->getComments($pageNumber, 10);

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