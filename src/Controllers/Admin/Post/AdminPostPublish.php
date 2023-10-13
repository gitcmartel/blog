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
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";

        if(UserActiveCheckValidity::check(array('Administrateur', 'Createur'))){
            $postRepository = new PostRepository(new DatabaseConnexion);

            if(isset($_POST['postPublish']) && isset($_POST['unpublish'])){
                //Updates the status post field
                switch(gettype($_POST['postPublish'])){
                    case "array":
                        foreach($_POST['postPublish'] as $postId){
                            $post = $postRepository->getPost($postId);
                            if($_POST['unpublish'] === "false"){
                                if($post->getPublicationDate() === null){
                                    $postRepository->setPublicationDate($postId);
                                }
                            } else {
                                $postRepository->setPublicationDateToNull($postId);
                            }
                        }
                        break;
                    case "string" :
                        $post = $postRepository->getPost($_POST['postPublish']);
                        if($_POST['unpublish'] === "false"){
                            if($post->getPublicationDate() === null){
                                $postRepository->setPublicationDate($_POST['postPublish']);
                            }
                        } else {
                            $postRepository->setPublicationDateToNull($_POST['postPublish']);
                        }
                        break;
                }
            }

            $totalPages = $postRepository->getTotalPageNumber(10);;
            if (isset($_GET['pageNumber'])){
                if($_GET['pageNumber'] !== 0){
                    $posts = $postRepository->getPosts($_GET['pageNumber'], 10);
                }
            } else {
                $posts = $postRepository->getPosts(1, 10);
            }
            
            $twig = TwigLoader::getEnvironment();
            
            echo $twig->render('Admin\Post\AdminPostList.html.twig', [ 
                'actualPage' => "1", 
                'totalPages' => $totalPages, 
                'posts' => $posts, 
                'activeUser' => Session::getActiveUser()
            ]);
            return;
        } else {
            $warningGeneral = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
            $warningLink = "index.php/action=Home\Home";
            $warningLinkMessage = "Nous contacter";
        }


        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Warning\notAllowed.html.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);
    }
}