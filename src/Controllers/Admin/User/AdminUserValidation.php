<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Models\UserActiveCheckValidity;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;
use Application\Lib\TwigLoader;

class AdminUserValidation 
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";

        if(UserActiveCheckValidity::check(array('Administrateur'))){
            if(isset($_POST['userValidation']) && isset($_POST['devalidate'])){
                //Updates the status user field
                switch(gettype($_POST['userValidation'])){
                    case "array":
                        foreach($_POST['userValidation'] as $userId){
                            $userRepository = new UserRepository(new DatabaseConnexion);
                            $user = $userRepository->getUser($userId);
                            if($_POST['devalidate'] === 'true'){
                                if($user->isValid){
                                    $userRepository->setValidation($userId, 0);
                                }
                            } else if($_POST['devalidate'] === 'false') {
                                if(! $user->isValid){
                                    $userRepository->setValidation($userId, -1);
                                }
                            }
                        }
                        break;
                    case "string" :
                        $user = $postRepository->getUser($_POST['userValidation']);
                        if($_POST['devalidate'] === 'true'){
                            if($user->isValid){
                                $postRepository->setValidation($_POST['userValidation'], 0);
                            }
                        } else if($_POST['devalidate'] === 'false'){
                            if($user->isValid){
                                $postRepository->setValidation($_POST['userValidation'], -1);
                            }
                        }
                        break;
                }
            }

            $totalPages = $userRepository->getTotalPageNumber(10);;
            if (isset($_GET['pageNumber'])){
                if($_GET['pageNumber'] !== 0){
                    $users = $userRepository->getUsers($_GET['pageNumber'], 10);
                }
            } else {
                $users = $userRepository->getUsers(1, 10);
            }
            
            $twig = TwigLoader::getEnvironment();
            
            echo $twig->render('Admin\User\AdminUserList.html.twig', [ 
                'actualPage' => "1", 
                'totalPages' => $totalPages, 
                'users' => $users, 
                'userFunction' => Session::getActiveUserFunction(),
                'activeUser' => Session::getActiveUser()
            ]);
            return;
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