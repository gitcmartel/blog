<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\Post;
use Application\Models\PostRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminPostModification
{
    public function execute()
    {
        if(UserActiveCheckValidity::check(array('Administrateur', 'Createur'))){
            if(isset($_GET['postId'])){
                if(trim($_GET['postId']) !== ""){
                    $postRepository = new PostRepository(new DatabaseConnexion);
                    $post = $postRepository->getPost($_GET['postId']);
    
                    $twig = TwigLoader::getEnvironment();
                    
                    echo $twig->render('Admin\Post\AdminPost.html.twig', [ 
                        'post' => $post, 
                        'activeUser' => Session::getActiveUser(), 
                        'userFunction' => Session::getActiveUserFunction()
                    ]);
                }
            }
        }
    }
}