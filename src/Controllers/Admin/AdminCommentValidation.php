<?php

namespace Application\Controllers\Admin;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Models\CommentRepository;
use Application\Models\Comment;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;

class AdminCommentValidation
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
                
                $loader = new \Twig\Loader\FilesystemLoader('templates');
                $twig = new \Twig\Environment($loader, ['cache' => false]);
                
                echo $twig->render('adminCommentList.twig', [ 
                    'actualPage' => "1", 
                    'totalPages' => $totalPages, 
                    'comments' => $comments, 
                    'userFunction' => $userFunction,
                    'activeUser' => Session::getActiveUser()
                ]);
                return;
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