<?php

namespace Application\Controllers\Admin\Comment;

use Application\Models\UserRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Models\CommentRepository;
use Application\Models\Comment;
use Application\Models\PostRepository;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminCommentSave
{
    //region Functions
    /**
     * Controller main function
     */
    public function execute(): void
    {
        //region variables
        $commentRepository = new CommentRepository();
        $postRepository = new PostRepository();
        $userRepository = new UserRepository();

        //endregion

        //region Conditions tests

        //If the active user is not an admin
        if (UserActiveCheckValidity::check(array('Administrateur')) === false) {
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site",
                "index.php?action=Home\Home",
                "Nous contacter");
            return;
        }

        $commentId = filter_input(INPUT_POST, 'commentId', FILTER_SANITIZE_NUMBER_INT);
        $postId = filter_input(INPUT_POST, 'postId', FILTER_SANITIZE_NUMBER_INT);
        $commentString = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);
        $validation = filter_input(INPUT_POST, 'validation', FILTER_SANITIZE_SPECIAL_CHARS);

        //If the commentId variable is not set
        if (
            $postId === false || $postId === null || $commentId === false || $commentId === null || $commentString === false
            || $commentString === null
        ) {
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.",
                "index.php?action=Home\Home",
                "Retour à la page d'accueil");
            return;
        }

        $comment = new Comment();

        $comment->hydrate(array(
            'id'              => (int) $commentId,
            'publicationDate' => ($validation !== false && $validation !== null) ? date('Y-m-d H:i:s') : null,
            'comment'         => $commentString,
            'user'            => $userRepository->getUser(Session::getActiveUserId()),
            'post'            => $postRepository->getPost($postId)
        ));

        $twig = TwigLoader::getEnvironment();

        //If the comment variable is empty
        if ($comment->getComment() === "") {
            echo $twig->render('Admin\Comment\AdminComment.html.twig', [
                'warningComment'  => "Vous devez renseigner un commentaire",
                'commentId'       => $comment->getId(),
                'postId'          => $comment->getPost()->getId(),
                'commentString'   => $comment->getComment(),
                'publicationDate' => $comment->getPublicationDate(),
                'userFunction'    => Session::getActiveUserFunction(),
                'activeUser'      => Session::getActiveUser()
            ]);
            return;
        }

        //endregion

        //region Function executions

        //If there is a commentId we update the comment field
        if ($comment->getId() !== 0) {
            $commentRepository->updateComment($comment);
            //We display the updated user list
            header("Location:index.php?action=Admin\Comment\AdminCommentList&pageNumber=1");
            return;
        }

        //If there is no commentId we create a new comment
        $commentRepository->createComment($comment);

        //We display the updated comment list
        header("Location:index.php?action=Admin\Comment\AdminCommentList&pageNumber=1");

        //endregion
    }

    //endregion
}
//end execute()
