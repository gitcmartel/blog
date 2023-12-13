<?php 

namespace Application\Controllers\Admin\Post;

use Application\Lib\UserActiveCheckValidity;
use Application\Models\PostRepository;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminPostSearch
{
    #region Functions
    public function execute()
    {
        #region Variables

        $postRepository = new PostRepository();
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

        $searchString = filter_input(INPUT_POST, 'searchString', FILTER_SANITIZE_SPECIAL_CHARS);

        //If the searchString variable is not set
        if ($searchString === false || $searchString === null){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        #endregion

        #region Function execution

        $posts = $postRepository->searchPosts(trim($searchString));

        echo $twig->render('Admin\Post\AdminPostList.html.twig', [ 
            'actualPage' => "1", 
            'totalPages' => "1", 
            'posts' => $posts, 
            'searchString' => $searchString, 
            'activeUser' => Session::getActiveUser(),
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}