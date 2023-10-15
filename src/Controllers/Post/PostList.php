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
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository(new DatabaseConnexion);
            $user = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $user->userFunction;
        } else {
            $userFunction = "";
        } 

        $postRepository = new PostRepository(new DatabaseConnexion);
        $totalPages = $postRepository->getTotalPageNumber(4);
        $pageNumber = 1;
        if (isset($_GET['pageNumber'])){
            if($_GET['pageNumber'] !== 0){
                $posts = $postRepository->getPosts($_GET['pageNumber'], 4);
                $pageNumber = $_GET['pageNumber'];
            }
        } else {
            $posts = $postRepository->getPosts(1, 4);
        }

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Post\PostList.html.twig', [ 
            'actualPage' => $pageNumber, 
            'totalPages' => $totalPages, 
            'posts' => $posts, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);
    }
    #endregion
}