<?php

namespace Application\Controllers\Admin\Comment;

use Application\Models\UserRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Models\CommentRepository;
use Application\Models\Comment;
use Application\Models\PostRepository;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminCommentSave
{
    #region Functions
    public function execute()
    {
        #region variables
        $commentRepository = new CommentRepository();
        $postRepository = new PostRepository();
        $userRepository = new UserRepository();

        #endregion

        #region Conditions tests

        //If the active user is not an admin
        if(! UserActiveCheckValidity::check(array('Administrateur'))){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;
        }

        //If the commentId variable is not set
        if (! isset($_POST['commentId']) || ! isset($_POST['postId']) || ! isset($_POST['comment'])){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        $comment = new Comment();

        $comment->hydrate(array (
            'id' => trim($_POST["commentId"]) === '' ? null : intval(trim($_POST["commentId"])),
            'publicationDate' => isset($_POST['validation']) ? date('Y-m-d H:i:s') : null,
            'comment' => trim($_POST['comment']), 
            'user' => $userRepository->getUser(Session::getActiveUserId()), 
            'post' => $postRepository->getPost($_POST["postId"])
        ));

        $twig = TwigLoader::getEnvironment();

        //If the comment variable is empty
        if($comment->getComment() === ""){
            echo $twig->render('Admin\Comment\AdminComment.html.twig', [ 
                'warningComment' => "Vous devez renseigner un commentaire", 
                'commentId' => $comment->getId(), 
                'postId' => $comment->getPost()->getId(), 
                'commentString' => $comment->getComment(), 
                'publicationDate' => $comment->getPublicationDate(),
                'userFunction' => Session::getActiveUserFunction(),
                'activeUser' => Session::getActiveUser()
            ]);
            return;
        }

        //Check if the commentId and postId variables are present in the database
        $commentDatabase = $commentRepository->getComment($comment->getId());

        if(($comment->getId() !== null && ($commentDatabase->getId() === null || $comment->getPost()->getId() === null)) || 
            ($comment->getId() === null && $comment->getPost()->getId() === null)){
            TwigWarning::display(
                "Un problème est survenu lors de l'enregistrement du commentaire.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return; 
        }

        #endregion

        #region Function executions

        //If there is a commentId we update the comment field
        if ($comment->getId() !== null){
            $commentRepository->updateComment($comment->getComment(), 
            $comment->getPublicationDate(), $comment->getId());
            //We display the updated user list
            header("Location:index.php?action=Admin\Comment\AdminCommentList&pageNumber=1");
            return;
        }

        //If there is no commentId we create a new comment

        $commentRepository->createComment($comment);

        //We display the updated comment list
        header("Location:index.php?action=Admin\Comment\AdminCommentList&pageNumber=1");

        #endregion
    }

    #endregion
}