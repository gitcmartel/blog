<?php

namespace Application\Controllers\Post;


use Application\Models\PostRepository;
use Application\Lib\Session;
use Application\Lib\Constants;
use Application\Lib\TwigLoader;

class PostList 
{
    #region Functions
    public function execute() 
    {
        #region Variables

        $postRepository = new PostRepository();
        $posts = "";
        $totalPages = 1;
        $pageNumber = 1;
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Function execution

        $totalPages = $postRepository->getTotalPageNumber(Constants::NUMBER_OF_BLOG_POST_PER_PAGE, true);
        
        if (isset($_GET['pageNumber'])){
            if($_GET['pageNumber'] !== 0){
                $posts = $postRepository->getPosts($_GET['pageNumber'], 4, true);
                $pageNumber = $_GET['pageNumber'];
            }
        } else {
            $posts = $postRepository->getPosts(1, Constants::NUMBER_OF_BLOG_POST_PER_PAGE, true);
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