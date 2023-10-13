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
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        $userFunction = "";

        if(UserActiveCheckValidity::check(array('Administrateur'))){
            $commentRepository = new CommentRepository();
            $totalPages = $commentRepository->getTotalPageNumber(10);
            $pageNumber = 1;
            if (isset($_GET['pageNumber'])){
                if($_GET['pageNumber'] !== 0){
                    $comments = $commentRepository->getComments($_GET['pageNumber'], 10);
                    $pageNumber = $_GET['pageNumber'];
                }
            } else {
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
            return;
        } else {
            $warningGeneral = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
            $warningLink = "index.php?action=Home\Home";
            $warningLinkMessage = "Nous contacter";
        }

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Warning\NotAllowed.html.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'activeUser' => Session::getActiveUser(),
            'userFunction' => Session::getActiveUserFunction()
        ]);
    }
}