<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminUserValidation 
{
    #region Functions
    public function execute()
    {
        #region Variables

        $userRepository = new UserRepository(new DatabaseConnexion);
        $user = "";
        $twig = TwigLoader::getEnvironment();
        $usersToValidate = "";
        $totalPages = "";

        #endregion

        #region Conditions tests

        if(! UserActiveCheckValidity::check(array('Administrateur')) || ! isset($_POST['userValidation']) 
            || ! isset($_POST['devalidate'])){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php/action=Home\Home", 
                "Nous contacter");
            return; 
        }

        #endregion
 
        #region Function execution

        $usersToValidate = is_array($_POST['userValidation']) ? $_POST['userValidation'] : [$_POST['userValidation']];

        //Updates the status user field

        foreach($usersToValidate as $userId){
            $user = $userRepository->getUser($userId);
            if($_POST['devalidate'] === 'true'){
                if($user->getIsValid()){
                    $userRepository->setValidation($userId, 0);
                }
            } else if($_POST['devalidate'] === 'false') {
                if(! $user->getIsValid()){
                    $userRepository->setValidation($userId, -1);
                }
            }
        }

        $totalPages = $userRepository->getTotalPageNumber(10);

        if (isset($_GET['pageNumber'])){
            if($_GET['pageNumber'] !== 0){
                $users = $userRepository->getUsers($_GET['pageNumber'], 10);
            }
        } else {
            $users = $userRepository->getUsers(1, 10);
        }
        
        echo $twig->render('Admin\User\AdminUserList.html.twig', [ 
            'actualPage' => "1", 
            'totalPages' => $totalPages, 
            'users' => $users, 
            'userFunction' => Session::getActiveUserFunction(),
            'activeUser' => Session::getActiveUser()
        ]);

        #endregion
    }
    #endregion
}