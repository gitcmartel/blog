<?php 

namespace Application\Controllers\Admin\Post;

use Application\Lib\UserActiveCheckValidity;
use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminPostSearch
{
    #region Functions
    public function execute()
    {
        #region Variables

        $postRepository = new PostRepository(new DatabaseConnexion);
        $posts = "";
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests
        if(! UserActiveCheckValidity::check(array('Administrateur', 'Createur')) || ! isset($_POST['searchString'])){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return;
        }

        if(trim($_POST['searchString']) === ""){
            return;
        }
        #endregion

        #region Function execution

        $posts = $postRepository->searchPosts(trim($_POST['searchString']));

        echo $twig->render('Admin\Post\AdminPostList.html.twig', [ 
            'actualPage' => "1", 
            'totalPages' => "1", 
            'posts' => $posts, 
            'activeUser' => Session::getActiveUser(),
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}