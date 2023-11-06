<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminPostPublish 
{
    #region Functions
    public function execute()
    {
        #region Variables

        $postRepository = new PostRepository(new DatabaseConnexion);
        $postsToPublish = "";
        $pageNumber = 1;
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

        //Paging
        if (isset($_GET['pageNumber']) && $_GET['pageNumber'] !== 0){
            $pageNumber = $_GET['pageNumber'];
        }

        $postsToPublish = is_array($_POST['postPublish']) ? $_POST['postPublish'] : [$_POST['postPublish']];

        //Check if all the commentid's are present in the database and if the validation variable is present
        if(! $postRepository->checkIds($postsToPublish, 'post', 'id')){
            TwigWarning::display(
                "Une erreur est survenue lors de la publication du ou des posts.",
                "index.php?action=Admin\Comment\AdminCommentList&pageNumber=1",
                "Retour à la page des commentaires"
            );
            return;
        }

        #endregion

        #region Function execution
        
        

        //Updates the status post field
        foreach($postsToPublish as $postId){
            $postRepository->getPost($postId);
            if($_POST['unpublish'] === "false"){
                $postRepository->setPublicationDate($postId);
            } else {
                $postRepository->setPublicationDateToNull($postId);
            }
        }
        
        $posts = $postRepository->getPosts($pageNumber, 10);

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