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
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";

        if(UserActiveCheckValidity::check(array('Administrateur', 'Createur'))){
            if(isset($_POST['searchString'])){
                if(trim($_POST['searchString']) !== ""){
                    $postRepository = new PostRepository(new DatabaseConnexion);
                    $posts = $postRepository->searchPosts(trim($_POST['searchString']));

                    $twig = TwigLoader::getEnvironment();
                    
                    echo $twig->render('Admin\Post\AdminPostList.html.twig', [ 
                        'actualPage' => "1", 
                        'totalPages' => "1", 
                        'posts' => $posts, 
                        'activeUser' => Session::getActiveUser(),
                        'userFunction' => Session::getActiveUserFunction()
                    ]);
                    return;
                }
            }
        } else {
            $warningGeneral = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
            $warningLink = "index.php/action=Home\Home";
            $warningLinkMessage = "Nous contacter";
        }


        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Warning\NotAllowed.html.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);    
    }
    #endregion
}