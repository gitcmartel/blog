<?php

namespace Application\Controllers\Admin;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Models\Comment;
use Application\Models\CommentRepository;
use Application\Lib\Session;

class AdminCommentList
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        $userFunction = "";
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository();
            $activeUser = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $activeUser->userFunction;
            if($activeUser->isAdmin() && $activeUser->isValid){
                $commentRepository = new CommentRepository();
                $totalPages = $commentRepository->getTotalPageNumber(10);;
                if (isset($_GET['pageNumber'])){
                    if($_GET['pageNumber'] !== 0){
                        $comments = $commentRepository->getComments($_GET['pageNumber'], 10);
                    }
                } else {
                    $comments = $commentRepository->getComments(1, 10);
                }
                
                $loader = new \Twig\Loader\FilesystemLoader('templates');
                
                $twig = new \Twig\Environment($loader, ['cache' => false, 'debug' => true]);
                $twig->addExtension(new \Twig\Extension\DebugExtension());
                
                
                echo $twig->render('adminCommentList.twig', [ 
                    'actualPage' => $_GET['pageNumber'], 
                    'totalPages' => $totalPages, 
                    'comments' => $comments, 
                    'activeUser' => Session::getActiveUser(), 
                    'userFunction' => $userFunction
                ]);
                return;
            } else {
                $warningGeneral = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
                $warningLink = "index.php?action=Home";
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
            'activeUser' => Session::getActiveUser(),
            'userFunction' => $userFunction
        ]);
    }
}