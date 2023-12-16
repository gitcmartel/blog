<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminPostPublish
{
    #region Functions
    public function execute()
    {
        #region Variables

        $postRepository = new PostRepository();
        $postsToPublish = "";
        $posts = "";
        $totalPages = $postRepository->getTotalPageNumber(10, false);
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        $options = array(
            'postValidation' => array(
                'filter' => FILTER_VALIDATE_INT,
                'flags' => FILTER_REQUIRE_ARRAY
            )
        );

        $postValidation = filter_input_array(INPUT_POST, $options);

        if (!UserActiveCheckValidity::check(array('Administrateur', 'Createur'))) {
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site",
                "index.php?action=Home\Home",
                "Nous contacter");
            return;
        }

        $pageNumber = filter_input(INPUT_GET, 'pageNumber', FILTER_SANITIZE_NUMBER_INT);
        $validation = filter_input(INPUT_POST, 'validation', FILTER_VALIDATE_BOOLEAN);

        //Paging
        if ($pageNumber === false || $pageNumber === null || $pageNumber === '0') {
            $pageNumber = 1;
        }

        $postsToPublish = is_array($postValidation) ? $postValidation : [$postValidation];

        //Check if all the commentid's are present in the database and if the validation variable is present
        if ($postsToPublish['postValidation'][0] === false || !$postRepository->checkIds($postsToPublish['postValidation'], 'post', 'id')) {
            TwigWarning::display(
                "Une erreur est survenue lors de la publication du ou des posts.",
                "index.php?action=Admin\Comment\AdminCommentList&pageNumber=1",
                "Retour à la page des commentaires"
            );
            return;
        }

        $validation = boolval($validation);

        #endregion

        #region Function execution


        //Updates the status post field
        foreach ($postsToPublish['postValidation'] as $postId) {
            $postRepository->getPost($postId);
            if ($validation === false) {
                $postRepository->setPublicationDateToNull($postId);
            } else {
                $postRepository->setPublicationDate($postId);
            }
        }

        $posts = $postRepository->getPosts($pageNumber, 10, false);

        //Page display
        echo $twig->render('Admin\Post\AdminPostList.html.twig', [
            'actualPage' => "1",
            'totalPages' => $totalPages,
            'posts' => $posts,
            'userFunction' => Session::getActiveUserFunction(),
            'activeUser' => Session::getActiveUser()
        ]);

        #endregion
    }
    #endregion
}