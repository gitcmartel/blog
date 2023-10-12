<?php

namespace Application\Controllers\Admin\Comment;

use Application\Models\UserActiveCheckValidity;
use Application\Models\Comment;
use Application\Models\CommentRepository;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminCommentDeletion
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        $userFunction = "";

        if(UserActiveCheckValidity::check(array('Administrateur'))){
            if (isset($_GET['commentId'])){
                if(trim($_GET['commentId']) !== ""){
                    $commentRepository = new CommentRepository();
                    $comment = $commentRepository->getComment(trim($_GET['commentId']));
                    if(isset($comment->id)){
                        if (! $commentRepository->deleteComment($comment)) {
                            $warningGeneral = "Un problème est survenu lors de la suppression du commentaire";
                            $warningLink = "index.php?action=AdminCommentList&pageNumber=1";
                            $warningLinkMessage = "Retour à la liste des commentaires";
                        } else  {
                            //We display the updated comment list
                            header('Location:index.php?action=Admin\Comment\AdminCommentList&pageNumber=1');
                            return;
                        }
                    }
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