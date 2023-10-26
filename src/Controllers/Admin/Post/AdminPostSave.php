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
use Application\Lib\Image;


class AdminPostSave
{
    #region Functions
    public function execute()
    {
        #region Variables
        var_dump($_POST);
        var_dump($_FILES);
        exit;
        $postRepository = new PostRepository(new DatabaseConnexion);
        $userRepository = new UserRepository(new DatabaseConnexion);
        $warningImage = '';
        $tmpImagePath = '';
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        if(! UserActiveCheckValidity::check(array('Administrateur', 'Createur'))){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;   
        }

        if(! isset($_POST['postTitle']) || ! isset($_POST['postSummary']) || ! isset($_POST['postContent']) 
        || ! isset($_POST['postId']) || ! isset($_POST['resetImage'])){
        TwigWarning::display(
            "Un problème est survenu lors de l'enregistrement du post.", 
            "index.php?action=Home\Home", 
            "Retour à la page d'accueil");
        return;   
        }

        if(isset($_FILES['imagePath']) && $_FILES['imagePath']['size'] > 0){
            $warningImage = upload::ckeckFile($_FILES['imagePath']['name'], $_FILES['imagePath']['size']);
            $tmpImagePath = $_FILES["imagePath"]["tmp_name"];
        }

        $pageVariables = array(
            'id' => trim($_POST["postId"]),
            'title' => trim($_POST["postTitle"]), 
            'summary' => trim($_POST['comment']), 
            'content' => trim($_POST['content']), 
            'tmpImagePath' => $tmpImagePath,
            'resetImage' => $_POST['resetImage'], 
            'user' => $userRepository->getUser(Session::getActiveUserId()), 
            'modifier' => $userRepository->getUser(Session::getActiveUserId())
        );

        $fieldsWarnings = array(
            'title' => 'Vous devez renseigner un titre', 
            'summary' => 'Vous devez renseigner un résumé', 
            'content' => 'Vous devez renseigner un contenu', 
            'image' => $warningImage, 
        );

        if($pageVariables['title'] !== "" || $pageVariables['summary'] !== "" || $pageVariables['content'] !=="" || $pageVariables['image'] !== ""){
            echo $twig->render('Admin\Post\AdminPost.html.twig', [ 
                'warningTitle' => $pageVariables['title'] === '' ? $fieldsWarnings['title'] : '', 
                'warningSummary' => $pageVariables['summary'] === '' ? $fieldsWarnings['summary'] : '',
                'warningContent' => $pageVariables['content'] === '' ? $fieldsWarnings['content'] : '',
                'warningImage' => $fieldsWarnings['image'], 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => Session::getActiveUserFunction()
            ]);
            return;
        }

        #endregion

        #region Function execution
        $post = new Post($pageVariables);

        //If there is a Post Id then we have to make an update
        if($pageVariables['id'] !== ""){ 
            if (! $postRepository->updatePost($post)){
                TwigWarning::display(
                    "Un problème est survenu lors de l'enregistrement du post.", 
                    "index.php?action=Home\Home", 
                    "Retour à l'accueil");
                return; 
            }

            $postRepository->updateImagePath($post, $pageVariables["tmpImagePath"]);

            //We display the updated post list
            header("Location:index.php?action=Admin\Post\AdminPostList&pageNumber=1");
            return;
        }

        //Else we have to create a new post

        if(! $postRepository->createPost($post)){
            TwigWarning::display(
                "Un problème est survenu lors de l'enregistrement du post.", 
                "index.php?action=Home\Home", 
                "Retour à l'accueil");
            return;  
        } 

        $postRepository->updateImagePath($post, $pageVariables["tmpImagePath"]);

        //We display the updated post list
        header("Location:index.php?action=Admin\Post\AdminPostList&pageNumber=1");

        #endregion
    }
    #endregion
}