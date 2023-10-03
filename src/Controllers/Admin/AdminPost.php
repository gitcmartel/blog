<?php

namespace Application\Controllers\Admin;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;

class AdminPost
{
    public function execute() 
    {
        $post = "";
        //If it's an existing post
        if(isset($_GET['postId'])){
            $postRepository = new PostRepository(new DatabaseConnexion);
            $post = $postRepository->getPost($_GET['postId']);
        }

        $userFunction = "";
        //Get the function of the active user
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository();
            $user = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $user->userFunction;
        }

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, ['cache' => false]);
        
        echo $twig->render('post.twig', [ 
            'post' => $post, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => $userFunction
        ]);
    }
}