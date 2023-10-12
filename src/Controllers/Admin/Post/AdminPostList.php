<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Models\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminPostList 
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";

        if(UserActiveCheckValidity::check(array('Administrateur', 'Createur'))){
            $postRepository = new PostRepository(new DatabaseConnexion);
            $totalPages = $postRepository->getTotalPageNumber(10);;
            $pageNumber = 1;
            if (isset($_GET['pageNumber'])){
                if($_GET['pageNumber'] !== 0){
                    $posts = $postRepository->getPosts($_GET['pageNumber'], 10);
                    $pageNumber = $_GET['pageNumber'];
                }
            } else {
                $posts = $postRepository->getPosts(1, 10);
            }
            
            $twig = TwigLoader::getEnvironment();
            
            echo $twig->render('Admin\Post\AdminPostList.html.twig', [ 
                'actualPage' => $pageNumber, 
                'totalPages' => $totalPages, 
                'posts' => $posts, 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => (isset($_SESSION['activeUserFunction'])? $_SESSION['activeUserFunction']:'')
            ]);
            return;
        } else {
            $warningGeneral = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
            $warningLink = "index.php?action=Home\Home";
            $warningLinkMessage = "Nous contacter";
        }

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Warning\notAllowed.html.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'activeUser' => Session::getActiveUser(),
            'userFunction' => Session::getActiveUserFunction()
        ]);
    }
}