<?php

namespace Application\Controllers\Admin\Comment;

use Application\Lib\UserActiveCheckValidity;
use Application\Models\Comment;
use Application\Models\CommentRepository;
use Application\Models\Post;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminCommentModification
{
    #region Functions
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        $userFunction = "";

        if(UserActiveCheckValidity::check(array('Administrateur'))){
            if (isset($_GET['commentId'])){
                $commentRepository = new CommentRepository();
                $comment = $commentRepository->getComment($_GET['commentId']);
                $post = new Post();

                $twig = TwigLoader::getEnvironment();
                
                echo $twig->render('Admin\Comment\AdminComment.html.twig', [  
                    'comment' => $comment,
                    'post' => $post,  
                    'activeUser' => Session::getActiveUser(), 
                    'userFunction' => Session::getActiveUserFunction()
                ]);
                return;
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
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);
    }
    #endregion
}