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
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";

        if(UserActiveCheckValidity::check(array('Administrateur'))){
            if(isset($_POST['commentValidation']) && isset($_POST['devalidate'])){
                $commentRepository = new CommentRepository();
                //Updates the comment publicationDate field
                switch(gettype($_POST['commentValidation'])){
                    case "array":
                        foreach($_POST['commentValidation'] as $commentId){
                            $comment = $commentRepository->getComment($commentId);
                            if($_POST['devalidate'] === 'true'){
                                $commentRepository->setPublicationDateToNull($commentId);
                            } else if($_POST['devalidate'] === 'false') {
                                $commentRepository->setPublicationDate($commentId);
                            }
                        }
                        break;
                    case "string" :
                        $comment = $commentRepository->getComment($_POST['commentValidation']);
                        if($_POST['devalidate'] === 'true'){
                            $commentRepository->setPublicationDateToNull($_POST['commentValidation']);
                        } else if($_POST['devalidate'] === 'false'){
                            $commentRepository->setPublicationDate($_POST['commentValidation']);
                        }
                        break;
                }
            }

            $totalPages = $commentRepository->getTotalPageNumber(10);;
            if (isset($_GET['pageNumber'])){
                if($_GET['pageNumber'] !== 0){
                    $comments = $commentRepository->getComments($_GET['pageNumber'], 10);
                }
            } else {
                $comments = $commentRepository->getComments(1, 10);
            }
            
            $twig = TwigLoader::getEnvironment();
            
            echo $twig->render('Admin\Comment\AdminCommentList.html.twig', [ 
                'actualPage' => "1", 
                'totalPages' => $totalPages, 
                'comments' => $comments, 
                'userFunction' => Session::getActiveUserFunction(),
                'activeUser' => Session::getActiveUser()
            ]);
            return;
        } else {
            $warningGeneral = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
            $warningLink = "index.php/action=Home\Home";
            $warningLinkMessage = "Nous contacter";
        }

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Warning\NotAllowed.html.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'userFunction' => Session::getActiveUserFunction(),
            'activeUser' => Session::getActiveUser()
        ]);
    }
    #endregion
}