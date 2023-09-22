<?php

namespace Application\Controllers\Admin;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Models\Comment;
use Application\Models\CommentRepository;
use Application\Models\Post;
use Application\Models\PostRepository;
use Application\Lib\Session;


class AdminCommentSave
{
    public function execute()
    {
        $warningGlobal = "";
        $warningComment = "";
        $comment = new Comment();
        $post = new Post();
        $userFunction = "";

        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository();
            $activeUser = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $activeUser->userFunction;
            if($activeUser->isAdmin() && $activeUser->isValid){
                if(isset($_POST['comment']) && isset($_POST['commentId']) && isset($_POST['postId'])){
                    //Checks if the comment field is correct
                    if(trim($_POST['comment']) === ""){
                        $warningComment = "Vous devez renseigner un commentaire";
                        $comment->comment = "";
                        //Get the id's to send it back to the view if 
                        if($_POST['commentId'] !== "") {
                            $comment->id = $_POST['commentId'];
                        }  

                        if($_POST['postId'] !== "") {
                            $post->id = $_POST['postId'];
                        }  
                    } else {
                        $comment->comment = $_POST['comment'];
                    }

                    if($warningComment === ""){
                        $commentRepository = new CommentRepository();
                        if (trim($_POST['commentId'] !== "")){
                            //If there is a commentId we update the comment field

                            $comment = $commentRepository->getComment($_POST['commentId']);
                            $comment->comment = $_POST['comment'];

                            if (isset($_POST['validation'])){
                                if ($_POST['validation'] && $comment->publicationDate === null){
                                    $comment->publicationDate = date('Y-m-d H:i:s');
                                }
                            } else {
                                unset($comment->publicationDate);
                            }

                            if ($commentRepository->updateComment($comment)) {
                                //We display the updated user list
                                header("Location:index.php?action=AdminCommentList&pageNumber=1");
                                return;
                            } else {
                                $warningGlobal = "Une erreur est survenue lors de l'enregistrement des données";
                            }
                        } else { //If there is no commentId we create a new comment
                            $postRepository = new PostRepository();
                            $comment->post = $postRepository->getPost($_POST['postId']);
                            $comment->user = $activeUser;

                            if ($commentRepository->createComment($comment)){
                                //We display the updated comment list
                                header("Location:index.php?action=AdminCommentList&pageNumber=1");
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
        } else {
            $warningGlobal = "Veuillez-vous identifier pour pouvoir accéder à cette page";
        }

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, ['cache' => false]);
        
        echo $twig->render('adminComment.twig', [ 
            'warningGlobal' => $warningGlobal, 
            'warningComment' => $warningComment, 
            'comment' => $comment, 
            'post' => $post, 
            'userFunction' => $userFunction,
            'activeUser' => Session::getActiveUser()
        ]);
    }
}