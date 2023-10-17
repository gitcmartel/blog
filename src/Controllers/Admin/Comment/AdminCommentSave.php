<?php

namespace Application\Controllers\Admin\Comment;

use Application\Models\User;
use Application\Models\UserRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Models\Comment;
use Application\Models\CommentRepository;
use Application\Models\Post;
use Application\Models\PostRepository;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminCommentSave
{
    #region Functions
    public function execute()
    {
        #region variables
        $warningComment  = "";
        $comment = new Comment();
        $post = new Post();
        $commentRepository = new CommentRepository();
        $postRepository = new PostRepository(new DatabaseConnexion);
        $userRepository = new UserRepository(new DatabaseConnexion);

        #endregion

        #region Conditions tests

        //If the active user is not an admin
        if(! UserActiveCheckValidity::check(array('Administrateur'))){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php/action=Home\Home", 
                "Nous contacter");
            return;
        }

        //If the commentId variable is not set
        if (! isset($_POST['commentId'])){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php/action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        //If the postId variable is not set
        if (! isset($_POST['postId'])){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php/action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        //If the comment variable is not set
        if (! isset($_POST['comment'])){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php/action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        //If the commentId variable is empty
        if(trim($_POST['comment']) === ""){
            $warningComment = "Vous devez renseigner un commentaire";
            $comment->setComment('');

            //Get the id's to send it back to the view if there is one
            if($_POST['commentId'] !== "" && $_POST['commentId'] !== '0') {
                $comment->setId($_POST['commentId']);
            }  

            if($_POST['postId'] !== "" && $_POST['postId'] !== '0') {
                $post->setId($_POST['postId']);
            }  

            $twig = TwigLoader::getEnvironment();
        
            echo $twig->render('Admin\Comment\AdminComment.html.twig', [ 
                'warningComment' => $warningComment, 
                'comment' => $comment, 
                'post' => $post, 
                'userFunction' => Session::getActiveUserFunction(),
                'activeUser' => Session::getActiveUser()
            ]);
            return;
        }
        #endregion

        #region Function execution
        $comment->setComment($_POST['comment']);

        if (trim($_POST['commentId'] !== "") && $_POST['commentId'] !== null){
            //If there is a commentId we update the comment field

            $comment = $commentRepository->getComment($_POST['commentId']);
            $comment->setComment($_POST['comment']);

            if (isset($_POST['validation'])){
                if ($_POST['validation'] && $comment->getPublicationDate() === null){
                    $comment->setPublicationDate(date('Y-m-d H:i:s'));
                }
            } else {
                $comment->setPublicationDate(null);
            }

            if ($commentRepository->updateComment($comment)) {
                //We display the updated user list
                header("Location:index.php?action=Admin\Comment\AdminCommentList&pageNumber=1");
                return;
            } else {
                TwigWarning::display(
                    "Une erreur est survenue lors de l'enregistrement des données.", 
                    "index.php/action=Home\Home", 
                    "Retour à la page d'accueil");
                return;
            }
        } else { //If there is no commentId we create a new comment
            $comment->setPost($postRepository->getPost($_POST['postId']));
            $comment->setUser($userRepository->getUser(Session::getActiveUserId()));

            if (isset($_POST['validation'])){
                $comment->setPublicationDate(date('Y-m-d H:i:s'));
            }

            if ($commentRepository->createComment($comment)){
                //We display the updated comment list
                header("Location:index.php?action=Admin\Comment\AdminCommentList&pageNumber=1");
                return;
            } else {
                TwigWarning::display(
                    "Une erreur est survenue lors de l'enregistrement des données.", 
                    "index.php/action=Home\Home", 
                    "Retour à la page d'accueil");
                return;
            }
        }
        #endregion
    }
    #endregion
}