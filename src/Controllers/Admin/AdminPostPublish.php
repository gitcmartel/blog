<?php

namespace Application\Controllers\Admin;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;

class AdminPostPublish 
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository();
            $user = $userRepository->getUser($_SESSION['userId']);
            if($user->isCreator()  && $user->isValid){
                $postRepository = new PostRepository();

                if(isset($_POST['postPublish']) && isset($_POST['unpublish'])){
                    //Updates the status post field
                    switch(gettype($_POST['postPublish'])){
                        case "array":
                            foreach($_POST['postPublish'] as $postId){
                                $post = $postRepository->getPost($postId);
                                if($_POST['unpublish'] === "false"){
                                    if($post->publicationDate === ''){
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
                                if($post->publicationDate === ''){
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
                
                $loader = new \Twig\Loader\FilesystemLoader('templates');
                $twig = new \Twig\Environment($loader, ['cache' => false]);
                
                echo $twig->render('adminPostList.twig', [ 
                    'actualPage' => "1", 
                    'totalPages' => $totalPages, 
                    'posts' => $posts, 
                    'activeUser' => Session::getActiveUser()
                ]);
                return;
            } else {
                $warningGeneral = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
                $warningLink = "index.php/action=Home";
                $warningLinkMessage = "Nous contacter";
            }
        } else {
            $warningGeneral = "Veuillez-vous identifier pour pouvoir accéder à cette page";
            $warningLink = "index.php?action=Connexion";
            $warningLinkMessage = "Se connecter";
        }

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, ['cache' => false]);
        
        echo $twig->render('notAllowed.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'activeUser' => Session::getActiveUser()
        ]);
    }
}