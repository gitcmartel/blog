<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminPostList 
{
    #region Functions
    public function execute()
    {
        #region Variables

        $postRepository = new PostRepository(new DatabaseConnexion);
        $totalPages = $postRepository->getTotalPageNumber(10);;
        $pageNumber = 1;
        $posts = "";
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        if(! UserActiveCheckValidity::check(array('Administrateur', 'Createur'))){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;  
        }

        #endregion

        #region Function execution

        if (isset($_GET['pageNumber'])){
            if($_GET['pageNumber'] !== 0){
                $posts = $postRepository->getPosts($_GET['pageNumber'], 10);
                $pageNumber = $_GET['pageNumber'];
            }
        } else {
            $posts = $postRepository->getPosts(1, 10);
        }
        
        echo $twig->render('Admin\Post\AdminPostList.html.twig', [ 
            'actualPage' => $pageNumber, 
            'totalPages' => $totalPages, 
            'posts' => $posts, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);
        return;

        #endregion
    }
    #endregion
}