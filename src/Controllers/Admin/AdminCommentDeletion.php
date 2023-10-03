<?php

namespace Application\Controllers\Admin;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Models\Comment;
use Application\Models\CommentRepository;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;

class AdminCommentDeletion
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        $userFunction = "";
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository(new DatabaseConnexion);
            $activeUser = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $activeUser->userFunction;
            if($activeUser->isAdmin()  && $activeUser->isValid){
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
                                header("Location:index.php?action=AdminCommentList&pageNumber=1");
                                return;
                            }
                        }
                    }
                }
            } else {
                $warningGeneral = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
                $warningLink = "index.php/action=Home";
                $warningLinkMessage = "Nous contacter";
            }
        } else {
            $warningGeneral = "Veuillez-vous identifier pour pouvoir accéder à cette page";
            $warningLink = "index.php?action=Connexion";
            $warningLinkMessage = "Se connecter";
        }

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, ['cache' => false]);
        
        echo $twig->render('notAllowed.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'userFunction' => $userFunction,
            'activeUser' => Session::getActiveUser()
        ]);
    }
}