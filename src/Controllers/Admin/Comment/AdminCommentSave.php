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

        $pageVariables = array(
            'id' => trim($_POST["commentId"]) === '' ? null : intval(trim($_POST["commentId"])),
            'publicationDate' => isset($_POST['validation']) ? date('Y-m-d H:i:s') : null,
            'comment' => trim($_POST['comment']), 
            'user' => $userRepository->getUser(Session::getActiveUserId()), 
            'post' => $postRepository->getPost($_POST["postId"])
        );

        $twig = TwigLoader::getEnvironment();

        //If the comment variable is empty
        if($pageVariables['comment'] === ""){
            echo $twig->render('Admin\Comment\AdminComment.html.twig', [ 
                'warningComment' => "Vous devez renseigner un commentaire", 
                'commentId' => $pageVariables['id'], 
                'postId' => $pageVariables['post']->getId(), 
                'commentString' => $pageVariables['comment'], 
                'publicationDate' => $pageVariables['publicationDate'],
                'userFunction' => Session::getActiveUserFunction(),
                'activeUser' => Session::getActiveUser()
            ]);
            return;
        }

        //Check if the commentId and postId variables are present in the database
        $commentDatabase = $commentRepository->getComment($pageVariables['id']);

        if($commentDatabase->getId() !== null && $pageVariables['post']->getId() !== null){
            TwigWarning::display(
                "Un problème est survenu lors de l'enregistrement du commentaire.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return; 
        }

        #endregion

        #region Function executions

        //If there is a commentId we update the comment field
        if ($pageVariables['id'] !== null){
            if ($commentRepository->updateComment($pageVariables['comment'], 
                $pageVariables['publicationDate'], $pageVariables['id'])) {
                //We display the updated user list
                header("Location:index.php?action=Admin\Comment\AdminCommentList&pageNumber=1");
                return;
            } else {
                TwigWarning::display(
                    "Une erreur est survenue lors de l'enregistrement des données.", 
                    "index.php?action=Home\Home", 
                    "Retour à la page d'accueil");
                return;
            }
        }

        //If there is no commentId we create a new comment
        $comment = new Comment($pageVariables);

        $commentRepository->createComment($comment);

        //We display the updated comment list
        header("Location:index.php?action=Admin\Comment\AdminCommentList&pageNumber=1");

        #endregion
    }

    #endregion
}