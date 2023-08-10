<?php

namespace Application\Controllers\Admin;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;


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
        $warningGlobal = "";

        //If there is a Post Id then we have to make an update
        if(isset($_POST['postId'])){
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
                    $postTitle = $_POST['postSummary'];
                }

                if(trim($_POST['postContent']) === ""){
                    $warningContent = "Vous devez renseigner un contenu";
                } else {
                    $postTitle = $_POST['postContent'];
                }

                //If all fields are ok we proceed
                if($postTitle !== "" && $postSummary !== "" && $postContent !=""){
                    if(isset($_SESSION['userId'])){
                        $userRepository = new UserRepository();
                        $user = $userRepository->getUser($_SESSION['userId']);
                        if($user->isCreator()){
                            $postRepository = new PostRepository();
                            $post = new Post(
                                null, 
                                $_POST['postTitle'], 
                                $_POST['postSummary'], 
                                $_POST['postContent'],
                                null, 
                                null, 
                                null, 
                                $user, 
                                null
                            );
                            if (! $postRepository->createPost($post)){
                                $warningGlobal = "Un problème est survenu lors de l'enregistrement du post";
                            } else {
                                //We display the updated post list
                                (new AdminPostList())->execute();
                                return;
                            }
                        }else {
                            $warningGlobal = "Vous n'avez pas les droits requis pour créer un article";
                        }
                    } else {
                        $warningGlobal = "Erreur d'authentification, veuillez vous reconnecter";
                    }
                }
            }
        } else {
            if(isset($_POST['postTitle']) && isset($_POST['postSummary']) && isset($_POST['postContent'])){
                if(isset($_SESSION['userId'])){
                    $userRepository = new UserRepository();
                    $user = $userRepository->getUser($_SESSION['userId']);

                    if($user->isCreator()){
                        $postRepository = new PostRepository();
                        $post = new Post(
                            $_POST['postId'], 
                            $_POST['postTitle'], 
                            $_POST['postSummary'], 
                            $_POST['postContent'],
                            null, 
                            null, 
                            null, 
                            $user
                        );
                        if(! $postRepository->updatePost($post)){
                            $warningGlobal = "Un problème est survenu lors de l'enregistrement du post";
                        } else {
                            //We display the updated list
                            (new AdminPostList())->execute();
                            return;
                        }
                    }
                } else {
                    $warningGlobal = "Erreur d'authentification, veuillez vous reconnecter";
                }
            }
        }
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, ['cache' => false]);
        
        echo $twig->render('post.twig', [ 
            'warningGlobal' => $warningGlobal, 
            'warningTitle' => $warningTitle, 
            'warningSummary' => $warningSummary,
            'warningContent' => $warningContent,
            'activeUser' => Session::getActiveUser()
        ]);
    }
}