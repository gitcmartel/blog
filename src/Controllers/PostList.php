<?php

namespace Application\Controllers;

use Application\Models\Post;
use Application\Models\PostRepository;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;

class PostList 
{
    public function execute() 
    {
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository();
            $user = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $user->userFunction;
        } else {
            $userFunction = "";
        } 

        $postRepository = new PostRepository();
        $totalPages = $postRepository->getTotalPageNumber(4);;
        $pageNumber = 1;
        if (isset($_GET['pageNumber'])){
            if($_GET['pageNumber'] !== 0){
                $posts = $postRepository->getPosts($_GET['pageNumber'], 4);
                $pageNumber = $_GET['pageNumber'];
            }
        } else {
            $posts = $postRepository->getPosts(1, 4);
        }

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, ['cache' => false]);
        
        echo $twig->render('postList.html.twig', [ 
            'actualPage' => $pageNumber, 
            'totalPages' => $totalPages, 
            'posts' => $posts, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => $userFunction
        ]);
    }
}