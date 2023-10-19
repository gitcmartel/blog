<?php

namespace Application\Controllers\Post;

use Application\Models\Post;
use Application\Models\PostRepository;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class PostDisplay{
    #region Functions
    public function execute()
    {
        #region Variables

        $postRepository = new PostRepository(new DatabaseConnexion);
        $post = "";
        $twig = TwigLoader::getEnvironment();

        #endregion
        
        #region Conditions tests
        if(! isset($_GET['postId'])){
            TwigWarning::display(
                "Un problème est survenu lors de l'affichage de l'article.", 
                "index.php?action=Post\PostList", 
                "Retour à la liste des articles");
            return; 
        }

        if(trim($_GET['postId']) === ""){
            TwigWarning::display(
                "Un problème est survenu lors de l'affichage de l'article.", 
                "index.php?action=Post\PostList", 
                "Retour à la liste des articles");
            return; 
        }

        #endregion

        #region Function execution
                    
        $post = $postRepository->getPost($_GET['postId']);

        echo $twig->render('Post\PostDisplay.html.twig', [  
            'post' => $post, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}