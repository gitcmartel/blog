<?php

namespace Application\Controllers\Admin;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;
use Application\Lib\Upload;
use Application\Lib\Constants;


class AdminPostSave
{
    public function execute()
    {
        $postId = 0;
        $postTitle = "";
        $postSummary = "";
        $postContent = "";
        $warningTitle = "";
        $warningSummary = "";
        $warningContent = "";
        $warningImage = "";
        $pathImage = "";
        $warningGlobal = "";

        //Checks the active user
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository();
            $user = $userRepository->getUser($_SESSION['userId']);
            //If the active user is allowed to create or update posts we proceed
            if($user->isCreator()){
                if(isset($_POST['postTitle']) && isset($_POST['postSummary']) && isset($_POST['postContent'])){
                    //Checks if the fields are corrects
                    if(trim($_POST['postTitle']) === ""){
                        $warningTitle = "Vous devez renseigner un titre";
                    } else {
                        $postTitle = $_POST['postTitle'];
                    }
        
                    if(trim($_POST['postSummary']) === ""){
                        $warningSummary = "Vous devez renseigner un résumé";
                    } else {
                        $postSummary = $_POST['postSummary'];
                    }
        
                    if(trim($_POST['postContent']) === ""){
                        $warningContent = "Vous devez renseigner un contenu";
                    } else {
                        $postContent = $_POST['postContent'];
                    }

                    //if there is an image for the post
                    if(isset($_FILES['imagePath'])){
                        if($_FILES['imagePath']['size'] > 0) {
                            //Checks the image size
                            if(! Upload::checkSize($_FILES['imagePath']['size'])){
                                $warningImage = "La taille de l'image ne doit pas excéder 2 Mo";
                            }
                        }

                        if(trim($_FILES['imagePath']['name']) !== ""){
                            //Checks if the file type is an image
                            if(! Upload::checkFileType($_FILES['imagePath']['name'])){
                                $warningImage = "Le type de fichier doit être une image (jpeg, jpg, png, svg)";
                            }
                        }

                        if($warningImage === ""){
                            $pathImage = Constants::IMAGE_POST_PATH . $_FILES['imagePath']['name'];
                        }
                    }
                            
                    //If all fields are ok we proceed
                    if($postTitle !== "" && $postSummary !== "" && $postContent !="" && $warningImage === ""){
                        $postRepository = new PostRepository();
                        $post = new Post();
                        $post->title = $postTitle;
                        $post->summary = $postSummary;
                        $post->content = $postContent;
                        $post->imagePath = $pathImage;
                        $post->modifier = $user;

                        //If there is a Post Id then we have to make an update
                        if(isset($_POST['postId'])){
                            if($_POST['postId'] !== ""){
                                $post->id = $_POST['postId'];
                                if (! $postRepository->updatePost($post)){
                                    $warningGlobal = "Un problème est survenu lors de l'enregistrement du post";
                                } else {
                                    //We display the updated post list
                                    header("Location:index.php?action=AdminPostList");
                                    return;
                                }
                            } else { //Else we have to create a new post
                                $post->user = $user;
                                if(! $postRepository->createPost($post)){
                                    $warningGlobal = "Un problème est survenu lors de l'enregistrement du post";
                                } else {
                                    //We display the updated post list
                                    header("Location:index.php?action=AdminPostList");
                                    return;
                                }
                            }
                        }
                    }
                }
            } else {
                $warningGlobal = "Vous n'avez pas les droits requis pour créer un article";
            } 
        } else {
            $warningGlobal = "Erreur d'authentification, veuillez vous reconnecter";
        }

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, ['cache' => false]);
        
        echo $twig->render('post.twig', [ 
            'warningGlobal' => $warningGlobal, 
            'warningTitle' => $warningTitle, 
            'warningSummary' => $warningSummary,
            'warningContent' => $warningContent,
            'warningImage' => $warningImage, 
            'activeUser' => Session::getActiveUser()
        ]);
    }
}