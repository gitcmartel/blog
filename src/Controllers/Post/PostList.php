<?php

namespace Application\Controllers\Post;

use Application\Models\Post;
use Application\Models\PostRepository;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class PostList 
{
    #region Functions
    public function execute() 
    {
        #region Variables

        $postRepository = new PostRepository(new DatabaseConnexion);
        $posts = "";
        $totalPages = 1;
        $pageNumber = 1;
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Function execution

        $totalPages = $postRepository->getTotalPageNumber(4);
        
        if (isset($_GET['pageNumber'])){
            if($_GET['pageNumber'] !== 0){
                $posts = $postRepository->getPosts($_GET['pageNumber'], 4);
                $pageNumber = $_GET['pageNumber'];
            }
        } else {
            $posts = $postRepository->getPosts(1, 4);
        }
        
        echo $twig->render('Post\PostList.html.twig', [ 
            'actualPage' => $pageNumber, 
            'totalPages' => $totalPages, 
            'posts' => $posts, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}