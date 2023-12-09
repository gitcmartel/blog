<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\TwigWarning;

class AdminPostDeletion
{
    #region Functions

    public function execute()
    {
        #region Variables

        $postRepository = new PostRepository();
        $post = "";
        
        #endregion

        #region Conditions tests

        if(! UserActiveCheckValidity::check(array('Administrateur', 'Createur')) || ! isset($_GET['postId']) 
        || $_GET['postId'] === ""){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;  
        }

        $post = $postRepository->getPost($_GET['postId']);

        //Check if the postId exists in the database
        if($post->getId() === null){
            TwigWarning::display(
                "Un problème est survenu lors de la suppression du post.", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;  
        }
        
        #endregion

        #region Function execution

        $postRepository->deletePost($post);

        //We delete the image if there is one
        $post->deleteImage();

        //We display the updated post list
        header("Location:index.php?action=Admin\Post\AdminPostList&pageNumber=1");

        #endregion
    }
    #endregion
}