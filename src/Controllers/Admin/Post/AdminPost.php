<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminPost
{
    #region Functions
    public function execute() 
    {
        if(UserActiveCheckValidity::check(array('Administrateur', 'Createur'))){
            $post = "";
            //If it's an existing post
            if(isset($_GET['postId'])){
                $postRepository = new PostRepository(new DatabaseConnexion);
                $post = $postRepository->getPost($_GET['postId']);
            }
        }

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Admin\Post\AdminPost.html.twig', [ 
            'post' => $post, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);
    }
    #endregion
}