<?php

namespace Application\Controllers\Admin;

use Application\Models\Post;
use Application\Models\PostRepository;
use Application\Lib\Session;

class AdminPostModification
{
    public function execute()
    {
        if(isset($_GET['postId'])){
            if(trim($_GET['postId']) !== ""){
                $postRepository = new PostRepository();
                $post = $postRepository->getPost($_GET['postId']);

                $loader = new \Twig\Loader\FilesystemLoader('templates');
                $twig = new \Twig\Environment($loader, ['cache' => false]);
                
                echo $twig->render('post.twig', [ 
                    'post' => $post, 
                    'activeUser' => Session::getActiveUser()
                ]);
            }
        }
    }
}