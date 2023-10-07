<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\Post;
use Application\Models\PostRepository;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminPostDeletion
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository(new DatabaseConnexion);
            $user = $userRepository->getUser($_SESSION['userId']);
            if($user->isCreator()  && $user->isValid){
                if (isset($_GET['postId'])){
                    if($_GET['postId'] !== ""){
                        $postRepository = new PostRepository(new DatabaseConnexion);
                        $post = $postRepository->getPost($_GET['postId']);
                        if(isset($post->id)){
                            if (! $postRepository->deletePost($post)) {
                                $warningGeneral = "Un problème est survenu lors de la suppression du post";
                                $warningLink = "index.php?action=Admin\Post\AdminPostList&pageNumber=1";
                                $warningLinkMessage = "Retour à la liste de posts";
                            } else  {
                                //We delete the image if there is one
                                $post->deleteImage();
                                //We display the updated post list
                                header("Location:index.php?action=Admin\Post\AdminPostList&pageNumber=1");
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
        } else {
            $warningGeneral = "Veuillez-vous identifier pour pouvoir accéder à cette page";
            $warningLink = "index.php?action=Connexion\Connexion";
            $warningLinkMessage = "Se connecter";
        }

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Warning\notAllowed.html.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'activeUser' => Session::getActiveUser()
        ]);
    }
}