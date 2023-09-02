<?php

namespace Application\Controllers\Admin;

use Application\Models\Post;
use Application\Models\PostRepository;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;

class AdminPostDeletion
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository();
            $user = $userRepository->getUser($_SESSION['userId']);
            if($user->isCreator()){
                if (isset($_GET['postId'])){
                    if($_GET['postId'] !== ""){
                        $postRepository = new PostRepository();
                        $post = $postRepository->getPost($_GET['postId']);
                        if(isset($post->id)){
                            if (! $postRepository->deletePost($post)) {
                                $warningGeneral = "Un problème est survenu lors de la suppression du post";
                                $warningLink = "index.php?action=AdminPostList&pageNumber=1";
                                $warningLinkMessage = "Retour à la liste de posts";
                            } else  {
                                //We display the updated post list
                                header("Location:index.php?action=AdminPostList&pageNumber=1");
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
            'activeUser' => Session::getActiveUser()
        ]);
    }
}