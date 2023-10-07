<?php

namespace Application\Controllers\Admin\Comment;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Models\Comment;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminCommentCreation
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
            if($activeUser->isAdmin() && $activeUser->isValid){
                if (isset($_GET['postId'])){
                    if($_GET['postId'] !== ""){
                        $postRepository = new PostRepository(new DatabaseConnexion);
                        $post = $postRepository->getPost($_GET['postId']);
                        $comment = new Comment();

                        $twig = TwigLoader::getEnvironment();
        
                        echo $twig->render('Admin\Comment\AdminComment.html.twig', [  
                            'comment' => $comment, 
                            'post' => $post, 
                            'activeUser' => Session::getActiveUser(), 
                            'userFunction' => $userFunction
                        ]);
                        return;
                    }
                }

            } else {
                $warningGeneral = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
                $warningLink = "index.php/action=Home\Home";
                $warningLinkMessage = "Nous contacter";
            }
        } else {
            $warningGeneral = "Veuillez-vous identifier pour pouvoir accéder à cette page";
            $warningLink = "index.php?action=Connexion\Connexion";
            $warningLinkMessage = "Se connecter";
        }

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Warning\NotAllowed.html.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'userFunction' => $userFunction,
            'activeUser' => Session::getActiveUser()
        ]);
    }
}