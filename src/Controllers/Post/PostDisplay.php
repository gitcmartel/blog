<?php

namespace Application\Controllers\Post;

use Application\Models\PostRepository;
use Application\Models\CommentRepository;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;
use Application\Lib\Alert;

class PostDisplay
{
    //region Functions
    /**
     * Controller main function
     */
    public function execute(): void
    {
        //region Variables

        $postRepository = new PostRepository();
        $commentRepository = new CommentRepository();
        $post = "";
        $twig = TwigLoader::getEnvironment();

        //endregion

        $postId = filter_input(INPUT_GET, 'postId', FILTER_SANITIZE_NUMBER_INT);
        $alert = filter_input(INPUT_GET, 'alert', FILTER_SANITIZE_SPECIAL_CHARS);
        $alertType = filter_input(INPUT_GET, 'alertType', FILTER_SANITIZE_SPECIAL_CHARS);

        //region Conditions tests
        if ($postId === false || $postId === null) {
            TwigWarning::display(
                "Un problème est survenu lors de l'affichage de l'article.",
                "index.php?action=Post\PostList",
                "Retour à la liste des articles");
            return;
        }

        //endregion

        //region Function execution

        $post = $postRepository->getPost($postId);
        $comments = $commentRepository->getCommentsPost($post);

        echo $twig->render('Post\PostDisplay.html.twig', [
            'post'         => $post,
            'comments'     => $comments,
            'alert'        => ($alert !== false && $alert !== null) ? $alert : '',
            'alertMessage' => ($alertType !== false && $alertType !== null) ? Alert::getMessage($alertType) : '',
            'activeUser'   => Session::getActiveUser(),
            'activeUserId' => Session::getActiveUserId(),
            'userFunction' => Session::getActiveUserFunction()
        ]);

        //endregion
    }
    //endregion
}
//End execute()
