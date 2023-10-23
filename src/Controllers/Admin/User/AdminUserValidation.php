<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
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
        $twig = TwigLoader::getEnvironment();
        $totalPages = 1;
        $pageNumber = 1;

        #endregion

        #region Conditions tests

        if(! UserActiveCheckValidity::check(array('Administrateur')) || ! isset($_POST['userValidation']) 
            || ! isset($_POST['validation']) || $_POST['validation'] === ""){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return; 
        }

        if (isset($_GET['pageNumber']) && $_GET['pageNumber'] !== 0){
            $pageNumber = $_GET['pageNumber'];
        }

        #endregion
 
        #region Function execution
        
        $validation = boolval($_POST["validation"]);

        $usersToValidate = is_array($_POST['userValidation']) ? $_POST['userValidation'] : [$_POST['userValidation']];

        //Updates the status user field

        foreach($usersToValidate as $userId){
            $user = $userRepository->getUser($userId);
            if($user->getId() !== null){
                $userRepository->setValidation($userId, $validation);
            }
        }

        $users = $userRepository->getUsers($pageNumber, 10);

        $totalPages = $userRepository->getTotalPageNumber(10);
        
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