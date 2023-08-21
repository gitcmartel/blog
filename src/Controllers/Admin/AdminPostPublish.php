<?php

namespace Application\Controllers\Admin;

use Application\Models\PostRepository;
use Application\Models\Post;
use Application\Lib\Session;

class AdminPostPublish
{
    public function execute()
    {
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

        $posts = $postRepository->getPosts();
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, ['cache' => false]);
        
        echo $twig->render('adminPostList.twig', [ 
            'pageNumber' => 1, 
            'posts' => $posts, 
            'activeUser' => Session::getActiveUser()
        ]);
    }
}