<?php

namespace Application\Controllers\Post;

use Application\Models\PostRepository;
use Application\Models\CommentRepository;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class PostDisplay{
    #region Functions
    public function execute()
    {
        #region Variables

        $postRepository = new PostRepository();
        $commentRepository = new CommentRepository();
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
        $comments = $commentRepository->getCommentsPost($post);

        echo $twig->render('Post\PostDisplay.html.twig', [  
            'post' => $post, 
            'comments' => $comments,
            'activeUser' => Session::getActiveUser(), 
            'activeUserId' => Session::getActiveUserId(),
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}