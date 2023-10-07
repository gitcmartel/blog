<?php

namespace Application\Controllers\Post;

use Application\Models\Post;
use Application\Models\PostRepository;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

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
                    $postRepository = new PostRepository(new DatabaseConnexion);
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
                        $twig = TwigLoader::getEnvironment();
                        
                        echo $twig->render('Post\PostDisplay.html.twig', [  
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
        $warningLink = "index.php?action=Home\Home";
        $warningLinkMessage = "Nous contacter";

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Warning\NotAllowed.html.twig', [ 
            'warningGeneral' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'activeUser' => Session::getActiveUser(),
            'userFunction' => $userFunction
        ]);
    }
}