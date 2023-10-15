<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\Upload;
use Application\Lib\Constants;
use Application\Lib\Path;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;


class AdminPostSave
{
    #region Functions
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
        $pathImage = Path::fileBuildPath(array("img", "blog-post.svg"));
        $insertImage = false;
        $warningGlobal = "";


        //If the active user is allowed to create or update posts we proceed
        if(UserActiveCheckValidity::check(array('Administrateur', 'Createur'))){
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

                        if(trim($_FILES['imagePath']['name']) !== ""){
                            //Checks if the file type is an image
                            if(! Upload::checkFileType($_FILES['imagePath']['name'])){
                                $warningImage = "Le type de fichier doit être une image (jpeg, jpg, png, svg)";
                            }
                        }

                        if($warningImage === ""){
                            $pathImage = Constants::IMAGE_POST_PATH;
                            $insertImage = true;
                        }
                    }
                }
                        
                //If all fields are ok we proceed
                if($postTitle !== "" && $postSummary !== "" && $postContent !="" && $warningImage === ""){
                    $postRepository = new PostRepository(new DatabaseConnexion);
                    $post = new Post();
                    $userRepository = new UserRepository(new DatabaseConnexion);
                    $user = $userRepository->getUser(Session::getActiveUserId());
                    $post->setTitle($postTitle);
                    $post->setSummary($postSummary);
                    $post->setContent($postContent);
                    $post->setImagePath($pathImage);
                    $post->setModifier($user);

                    //If there is a Post Id then we have to make an update
                    if(isset($_POST['postId'])){
                        if($_POST['postId'] !== ""){
                            $post->setId($_POST['postId']);
                            $actualPost = $postRepository->getPost($_POST['postId']);  //Get the data from the database
                            //If the stored image path is different from the default image path
                            $post->setCreationDate($actualPost->creationDate);
                            if($actualPost->getImagePath() !== Path::fileBuildPath(array("img", "blog-post.svg")) && $post->getImagePath() === Path::fileBuildPath(array("img", "blog-post.svg")) && $_POST["resetImage"] === "false"){
                                $post->setImagePath($actualPost->getImagePath());
                            }
                            if (! $postRepository->updatePost($post)){
                                $warningGlobal = "Un problème est survenu lors de l'enregistrement du post";
                            } else {
                                if ($insertImage){ //If there is an image to insert
                                    $imagePath = $postRepository->updateImagePath($post, "." . Upload::getExtension($_FILES["imagePath"]["name"])); //Updates the new image path
                                    if ($imagePath !== "") {
                                        //We move the image into the img-posts folder
                                        $tmp_name = $_FILES["imagePath"]["tmp_name"];
                                        if (! move_uploaded_file($tmp_name, dirname(__FILE__, 5) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $imagePath)) {
                                            $warningImage =  "Le fichier est invalide";
                                        }
                                        //If the original image is not the default image
                                        if ($actualPost->getImagePath() !== Path::fileBuildPath(array("img", "blog-post.svg"))){
                                            //We delete the old image file
                                            if(file_exists($actualPost->getImagePath())){
                                                $actualPost->setImagePath(null);
                                            }
                                        }
                                    }
                                } else { //If the resetImage variable is true
                                    if ($_POST["resetImage"] === "true"){
                                        //We delete the old image file
                                        if(file_exists($actualPost->getImagePath())){
                                            $actualPost->setImagePath(null);
                                        }
                                    }
                                }
                                //We display the updated post list
                                header("Location:index.php?action=Admin\Post\AdminPostList&pageNumber=1");
                                return;
                            }
                        } else { //Else we have to create a new post
                            $post->user = $user;
                            if(! $postRepository->createPost($post)){
                                $warningGlobal = "Un problème est survenu lors de l'enregistrement du post";
                            } else {
                                if($insertImage){ //If there is an image to insert
                                    $imagePath = $postRepository->updateImagePath($post, "." . Upload::getExtension($_FILES["imagePath"]["name"]));
                                    if ($imagePath !== "") {
                                        //We move the image into the img-posts folder
                                        $tmp_name = $_FILES["imagePath"]["tmp_name"];
                                        if (! move_uploaded_file($tmp_name, dirname(__FILE__, 4) . DIRECTORY_SEPARATOR . $imagePath)) {
                                            $warningImage =  "Le fichier est invalide";
                                        }
                                    }
                                }
                                //We display the updated post list
                                header("Location:index.php?action=Admin\Post\AdminPostList&pageNumber=1");
                                return;
                            }
                        }
                    }
                }
            }
        } else {
            $warningGlobal = "Vous n'avez pas les droits requis pour créer un article";
        } 

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Admin\Post\AdminPost.html.twig', [ 
            'warningGlobal' => $warningGlobal, 
            'warningTitle' => $warningTitle, 
            'warningSummary' => $warningSummary,
            'warningContent' => $warningContent,
            'warningImage' => $warningImage, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);
    }
    #endregion
}