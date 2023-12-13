<?php

namespace Application\Controllers\Admin\Post;

use Application\Models\PostRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminPostCreation
{
    #region Functions
    public function execute() 
    {
        #region Variables
        $post = "";
        $postRepository = new PostRepository();

        #endregion

        #region Conditions tests

        if(! UserActiveCheckValidity::check(array('Administrateur', 'Createur'))){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;     
        }

        $postId = filter_input(INPUT_GET, 'postId', FILTER_SANITIZE_NUMBER_INT);

        #endregion

        #region Function execution
        if($postId === false || $postId === null){
            $post = $postRepository->getPost($postId );
        }
        
        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Admin\Post\AdminPost.html.twig', [ 
            'post' => $post, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);
        
        #endregion
    }
    #endregion
}