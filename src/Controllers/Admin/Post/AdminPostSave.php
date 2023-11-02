<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Models\UserRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\Upload;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;
use Exception;

class AdminPostSave
{
    #region Functions
    public function execute()
    {
        #region Variables

        $postRepository = new PostRepository();
        $userRepository = new UserRepository();
        $warningImage = '';
        $tmpImagePath = '';
        $imageName = '';
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

        if(isset($_FILES['imagePath']) && $_FILES['imagePath']['error'] === UPLOAD_ERR_OK && $_FILES['imagePath']['size'] > 0){
            $warningImage = upload::ckeckFile($_FILES['imagePath']['name'], $_FILES['imagePath']['size']);
            $tmpImagePath = $_FILES["imagePath"]["tmp_name"];
            $imageName = $_FILES["imagePath"]["name"];
        }

        $post = new Post();

        $post->hydrate(array(
            'id' => trim($_POST["postId"]) === '' ? null : intval(trim($_POST["postId"])),
            'title' => trim($_POST["postTitle"]), 
            'summary' => trim($_POST['postSummary']), 
            'content' => trim($_POST['postContent']), 
            'user' => $userRepository->getUser(Session::getActiveUserId()), 
            'modifier' => $userRepository->getUser(Session::getActiveUserId())
        ));

        $resetImage = $_POST['resetImage'];

        $fieldsWarnings = array(
            'title' => 'Vous devez renseigner un titre', 
            'summary' => 'Vous devez renseigner un résumé', 
            'content' => 'Vous devez renseigner un contenu', 
            'image' => $warningImage, 
        );

        if($post->getTitle() === "" || $post->getSummary() === "" || $post->getContent() ===""){
            echo $twig->render('Admin\Post\AdminPost.html.twig', [ 
                'warningTitle' => $post->getTitle() === '' ? $fieldsWarnings['title'] : '', 
                'warningSummary' => $post->getSummary() === '' ? $fieldsWarnings['summary'] : '',
                'warningContent' => $post->getContent() === '' ? $fieldsWarnings['content'] : '',
                'warningImage' => $fieldsWarnings['image'], 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => Session::getActiveUserFunction()
            ]);
            return;
        }

        //Check if the postId variable is present in the database
        $postDatabase = $postRepository->getPost($post->getId());

        if($post->getId() !== null && ($postDatabase->getId() !== (int)$post->getId())){
            TwigWarning::display(
                "Un problème est survenu lors de l'enregistrement du post.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return; 
        }

        
        #endregion

        #region Function execution

        //If there is a Post Id then we have to make an update
        if($post->getId() !== null){ 
            //Fetching creationDate and imagePath from the database
            $post->setCreationDate($postDatabase->getCreationDate());
            $post->setImagePath($postDatabase->getImagePath());
            if (! $postRepository->updatePost($post)){
                TwigWarning::display(
                    "Un problème est survenu lors de l'enregistrement du post.", 
                    "index.php?action=Home\Home", 
                    "Retour à l'accueil");
                return; 
            }
        } else {
            //Else we have to create a new post
            $postRepository->createPost($post);
        }

        //Image management function (deletes, update, move physical tmp image etc...)
        try {
            $postRepository->checkImage($resetImage, $tmpImagePath, 
            $imageName, $post);
        } catch (Exception $exception) {
            TwigWarning::display(
                "Un problème est survenu lors de l'enregistrement de l'image. \n 
                Desription de l'erreur : " . $exception->getMessage(), 
                "index.php?action=Admin\Post\AdminPostList", 
                "Retour à la liste des posts");
            return;  
        }

        //We display the updated post list
        header("Location:index.php?action=Admin\Post\AdminPostList&pageNumber=1");

        #endregion
    }
    #endregion
}