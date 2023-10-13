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
    public function execute()
    {
        $warningGlobal = "";
        $warningComment = "";
        $comment = new Comment();
        $post = new Post();

        if(UserActiveCheckValidity::check(array('Administrateur'))){
            if(isset($_POST['comment']) && isset($_POST['commentId']) && isset($_POST['postId'])){
                //Checks if the comment field is correct
                if(trim($_POST['comment']) === ""){
                    $warningComment = "Vous devez renseigner un commentaire";
                    $comment->setComment('');
                    //Get the id's to send it back to the view if 
                    if($_POST['commentId'] !== "" && $_POST['commentId'] !== '0') {
                        $comment->setId($_POST['commentId']);
                    }  

                    if($_POST['postId'] !== "" && $_POST['postId'] !== '0') {
                        $post->setId($_POST['postId']);
                    }  
                } else {
                    $comment->setComment($_POST['comment']);
                }

                if($warningComment === ""){
                    $commentRepository = new CommentRepository();
                    if (trim($_POST['commentId'] !== "") && $_POST['commentId'] !== '0'){
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
                            $warningGlobal = "Une erreur est survenue lors de l'enregistrement des données";
                        }
                    } else { //If there is no commentId we create a new comment
                        $postRepository = new PostRepository(new DatabaseConnexion);
                        $userRepository = new UserRepository(new DatabaseConnexion);
                        $comment->setPost($postRepository->getPost($_POST['postId']));
                        $comment->setUser($userRepository->getUser(Session::getActiveUserId()));

                        if (isset($_POST['validation'])){
                            $comment-setPpublicationDate(date('Y-m-d H:i:s'));
                        }

                        if ($commentRepository->createComment($comment)){
                            //We display the updated comment list
                            header("Location:index.php?action=Admin\Comment\AdminCommentList&pageNumber=1");
                            return;
                        } else {
                            $warningGlobal = "Une erreur est survenue lors de l'enregistrement des données";
                        }
                    }
                }
            }
        } else {
            $warningGlobal = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
        }

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Admin\Comment\AdminComment.html.twig', [ 
            'warningGlobal' => $warningGlobal, 
            'warningComment' => $warningComment, 
            'comment' => $comment, 
            'post' => $post, 
            'userFunction' => Session::getActiveUserFunction(),
            'activeUser' => Session::getActiveUser()
        ]);
    }
}