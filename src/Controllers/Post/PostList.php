<?php

namespace Application\Controllers\Post;


use Application\Models\PostRepository;
use Application\Lib\Session;
use Application\Lib\Constants;
use Application\Lib\TwigLoader;

class PostList
{
    //region Functions
    /**
     * Controller main function
     */
    public function execute(): void
    {
        //region Variables

        $postRepository = new PostRepository();
        $posts = "";
        $totalPages = 1;
        $twig = TwigLoader::getEnvironment();

        //endregion

        //region Function execution

        $totalPages = $postRepository->getTotalPageNumber(Constants::NUMBER_OF_BLOG_POST_PER_PAGE, true);
        $pageNumber = filter_input(INPUT_GET, 'pageNumber', FILTER_SANITIZE_NUMBER_INT);

        if ($pageNumber === false || $pageNumber === null || $pageNumber === '0') {
            $pageNumber = 1;
        }

        $posts = $postRepository->getPosts($pageNumber, Constants::NUMBER_OF_BLOG_POST_PER_PAGE, true);

        echo $twig->render('Post\PostList.html.twig', [
            'actualPage'   => $pageNumber,
            'totalPages'   => $totalPages,
            'posts'        => $posts,
            'activeUser'   => Session::getActiveUser(),
            'userFunction' => Session::getActiveUserFunction()
        ]);

        //endregion
    }
    //endregion
}
//End execute()
