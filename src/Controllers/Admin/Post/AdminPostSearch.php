<?php 

namespace Application\Controllers\Admin\Post;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

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
            $userFunction = $user->userFunction;
            if($user->isCreator()  && $user->isValid){
                if(isset($_POST['searchString'])){
                    if(trim($_POST['searchString']) !== ""){
                        $postRepository = new PostRepository(new DatabaseConnexion);
                        $posts = $postRepository->searchPosts(trim($_POST['searchString']));

                        $twig = TwigLoader::getEnvironment();
                        
                        echo $twig->render('Admin\Post\AdminPostList.html.twig', [ 
                            'actualPage' => "1", 
                            'totalPages' => "1", 
                            'posts' => $posts, 
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
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => $userFunction
        ]);    
    }
}