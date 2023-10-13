<?php

namespace Application\Controllers\Admin\Comment;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Lib\UserActiveCheckValidity;
use Application\Models\Comment;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

class AdminCommentCreation
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";

        if(UserActiveCheckValidity::check(array('Administrateur'))){
            if (isset($_GET['postId'])){
                if($_GET['postId'] !== ""){
                    $postRepository = new PostRepository(new DatabaseConnexion);
                    $post = $postRepository->getPost($_GET['postId']);
                    $comment = new Comment();

                    $twig = TwigLoader::getEnvironment();
    
                    echo $twig->render('Admin\Comment\AdminComment.html.twig', [  
                        'comment' => $comment, 
                        'post' => $post, 
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
            'userFunction' => Session::getActiveUserFunction(),
            'activeUser' => Session::getActiveUser()
        ]);
    }
}