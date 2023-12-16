<?php

namespace Application\Controllers\Comment;

use Application\Models\UserRepository;
use Application\Models\CommentRepository;
use Application\Models\Comment;
use Application\Models\PostRepository;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class CommentSave
{
    #region Functions
    /**
     * Controller main function execute
     */
    public function execute()
    {
        #region variables
        $commentRepository = new CommentRepository();
        $postRepository = new PostRepository();
        $userRepository = new UserRepository();

        #endregion

        #region Conditions tests

        //If the user is not logged in
        if(Session::getActiveUser() === ""){
            TwigWarning::display(
                "Vous devez vous connecter pour pouvoir ajouter un commentaire.", 
                "index.php?action=Connexion\Connexion", 
                "Se connecter");
            return;
        }

        $commentId = filter_input(INPUT_POST, 'commentId', FILTER_SANITIZE_NUMBER_INT);
        $postId = filter_input(INPUT_POST, 'postId', FILTER_SANITIZE_NUMBER_INT);
        $commentString = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);

        //If the commentId variable is not set
        if ($postId === false || $postId === null || $commentId === false || $commentId === null || $commentString === false 
        || $commentString === null){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        $comment = new Comment();

        $comment->hydrate(array (
            'id' => trim($commentId),
            'comment' => trim($commentString), 
            'publicationDate' => null,
            'user' => $userRepository->getUser(Session::getActiveUserId()), 
            'post' => $postRepository->getPost($postId)
        ));

        $twig = TwigLoader::getEnvironment();

        //If the comment variable is empty
        if($comment->getComment() === ""){
            echo $twig->render('Comment\Comment.html.twig', [ 
                'warningComment' => "Vous devez renseigner un commentaire", 
                'commentId' => $comment->getId(), 
                'postId' => $comment->getPost()->getId(), 
                'commentString' => $comment->getComment(), 
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
            $commentRepository->updateComment($comment);
            //We display the updated user list
            header("Location:index.php?action=Post\PostDisplay&postId=". $comment->getPost()->getId());
            return;
        }

        //If there is no commentId we create a new comment

        $commentRepository->createComment($comment);

        //We display the updated comment list
        header("Location:index.php?action=Post\PostDisplay&postId=" . $comment->getPost()->getId());

        #endregion
    }

    #endregion
}
