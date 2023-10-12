<?php

namespace Application\Controllers\Admin\Comment;

use Application\Models\UserActiveCheckValidity;
use Application\Models\Comment;
use Application\Models\CommentRepository;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminCommentSearch
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";

        if(UserActiveCheckValidity::check(array('Administrateur'))){
            if(isset($_POST['searchString'])){
                if(trim($_POST['searchString']) !== ""){
                    $commentRepository = new CommentRepository();
                    $comments = $commentRepository->searchComments(trim($_POST['searchString']));

                    $twig = TwigLoader::getEnvironment();
                    
                    echo $twig->render('Admin\Comment\AdminCommentList.html.twig', [ 
                        'actualPage' => "1", 
                        'totalPages' => "1", 
                        'comments' => $comments, 
                        'userFunction' => Session::getActiveUserFunction(),
                        'activeUser' => Session::getActiveUser()
                    ]);
                    return;
                }
            }
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
}