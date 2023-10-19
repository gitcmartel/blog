<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminPostPublish 
{
    #region Functions
    public function execute()
    {
        #region Variables

        $postRepository = new PostRepository(new DatabaseConnexion);
        $postsToPublish = "";
        $post =  "";
        $posts = "";
        $totalPages = $postRepository->getTotalPageNumber(10);
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        if(! UserActiveCheckValidity::check(array('Administrateur', 'Createur')) || ! isset($_POST['postPublish']) ||
            ! isset($_POST['unpublish'])){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;  
        }

        #endregion

        #region Function execution

        $postsToPublish = is_array($_POST['postPublish']) ? $_POST['postPublish'] : [$_POST['postPublish']];

        //Updates the status post field
        foreach($postsToPublish as $postId){
            $post = $postRepository->getPost($postId);
            if($_POST['unpublish'] === "false"){
                $postRepository->setPublicationDate($postId);
            } else {
                $postRepository->setPublicationDateToNull($postId);
            }
        }

        //Paging
        if (isset($_GET['pageNumber'])){
            if($_GET['pageNumber'] !== 0){
                $posts = $postRepository->getPosts($_GET['pageNumber'], 10);
            }
        } else {
            $posts = $postRepository->getPosts(1, 10);
        }
        
        //Page display
        echo $twig->render('Admin\Post\AdminPostList.html.twig', [ 
            'actualPage' => "1", 
            'totalPages' => $totalPages, 
            'posts' => $posts, 
            'userFunction' => Session::getActiveUserFunction(),
            'activeUser' => Session::getActiveUser()
        ]);

        #endregion
    }
    #endregion
}