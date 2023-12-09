<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\Constants;
use Application\Lib\TwigWarning;
use Application\Lib\Alert;

class AdminPostList 
{
    #region Functions
    public function execute()
    {
        #region Variables

        $postRepository = new PostRepository();
        $totalPages = $postRepository->getTotalPageNumber(Constants::NUMBER_OF_POSTS_PER_PAGE, false);;
        $pageNumber = 1;
        $posts = "";
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

        if (isset($_GET['pageNumber']) && $_GET['pageNumber'] !== 0){
            $pageNumber = $_GET['pageNumber'];
        }
        
        #endregion

        #region Function execution
        
        $posts = $postRepository->getPosts($pageNumber, Constants::NUMBER_OF_POSTS_PER_PAGE, false);

        echo $twig->render('Admin\Post\AdminPostList.html.twig', [ 
            'actualPage' => $pageNumber, 
            'totalPages' => $totalPages, 
            'posts' => $posts, 
            'alert' => isset($_GET['alert']) ? $_GET['alert'] : '',
            'alertMessage' => isset($_GET['alertType']) ? Alert::getMessage($_GET['alertType']) : '',
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);
        return;

        #endregion
    }
    #endregion
}