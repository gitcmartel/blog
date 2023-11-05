<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminUserValidation 
{
    #region Functions
    public function execute()
    {
        #region Variables

        $userRepository = new UserRepository();
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

        $validation = boolval($_POST["validation"]);

        $usersToValidate = is_array($_POST['userValidation']) ? $_POST['userValidation'] : [$_POST['userValidation']];

        //Check if all the userId's are present in the database
        if(! $userRepository->checkIds($usersToValidate, 'user', 'id')){
            TwigWarning::display(
                "Une erreur est survenue lors de la validation du ou des utilisateurs.",
                "index.php?action=Admin\Comment\AdminCommentList&pageNumber=1",
                "Retour à la page des commentaires"
            );
            return;
        }

        #endregion
 
        #region Function execution
        


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