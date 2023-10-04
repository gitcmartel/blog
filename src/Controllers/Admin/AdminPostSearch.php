<?php 

namespace Application\Controllers\Admin;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;

class AdminPostSearch
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
                if(isset($_POST['searchString'])){
                    if(trim($_POST['searchString']) !== ""){
                        $postRepository = new PostRepository(new DatabaseConnexion);
                        $posts = $postRepository->searchPosts(trim($_POST['searchString']));

                        $loader = new \Twig\Loader\FilesystemLoader('templates');
                        $twig = new \Twig\Environment($loader, ['cache' => false]);
                        
                        echo $twig->render('adminPostList.twig', [ 
                            'actualPage' => "1", 
                            'totalPages' => "1", 
                            'posts' => $posts, 
                            'activeUser' => Session::getActiveUser()
                        ]);
                        return;
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