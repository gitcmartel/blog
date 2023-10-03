<?php

namespace Application\Controllers;

use Application\Models\Post;
use Application\Models\PostRepository;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;

class PostDisplay{

    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";

        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository(new DatabaseConnexion);
            $user = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $user->userFunction;
        } else {
            $userFunction = "";
        } 

        if(isset($_GET['postId'])){
            if(trim($_GET['postId']) !== ""){
                if(is_numeric($_GET['postId'])){
                    $postRepository = new PostRepository();
                    $post = $postRepository->getPost($_GET['postId']);

                    $postIdList = $postRepository->getPostIdList();
                    $actualPostIndex = array_search($post->id, $postIdList);

                    $firstPostId = $postIdList[0];

                    $lastPostId = $postIdList[count($postIdList) -1];

                    if($post->id < $lastPostId ){
                        $nextPostId = $postIdList[array_search($post->id, $postIdList) + 1];
                    } else {
                        $nextPostId = $lastPostId;
                    }

                    if($post->id > $firstPostId ){
                        $previousPostId = $postIdList[array_search($post->id, $postIdList) - 1];
                    } else {
                        $previousPostId = $firstPostId;
                    }
                    
                    if($post->id === intval($_GET['postId'])){
                        $loader = new \Twig\Loader\FilesystemLoader('templates');
                        $twig = new \Twig\Environment($loader, ['cache' => false]);
                        
                        echo $twig->render('postDisplay.html.twig', [  
                            'firstPostId' => $firstPostId, 
                            'previousPostId' => $previousPostId, 
                            'nextPostId' => $nextPostId, 
                            'lastPostId' => $lastPostId, 
                            'post' => $post, 
                            'activeUser' => Session::getActiveUser(), 
                            'userFunction' => $userFunction
                        ]);
                        return;
                    }
                }
            }
        }

        $warningGeneral = "Une erreur est survenue lors de l'affichage de l'article";
        $warningLink = "index.php?action=Home";
        $warningLinkMessage = "Nous contacter";

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, ['cache' => false]);
        
        echo $twig->render('notAllowed.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'activeUser' => Session::getActiveUser(),
            'userFunction' => $userFunction
        ]);
    }
}