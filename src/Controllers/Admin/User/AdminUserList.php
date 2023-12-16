<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;
use Application\Lib\Constants;
use Application\Lib\Alert;

class AdminUserList 
{
    #region Functions
    public function execute()
    {
        #region Variables

        $userRepository = new UserRepository();
        $totalPages = 1;
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests
        
        $alert = filter_input(INPUT_GET, 'alert', FILTER_SANITIZE_SPECIAL_CHARS);
        $alertType = filter_input(INPUT_GET, 'alertType', FILTER_SANITIZE_SPECIAL_CHARS);
        $pageNumber = filter_input(INPUT_GET, 'pageNumber', FILTER_SANITIZE_NUMBER_INT);

        if(! UserActiveCheckValidity::check(array('Administrateur'))){
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                "index.php?action=Home\Home", 
                "Nous contacter");
            return; 
        }

        if ($pageNumber === false || $pageNumber === null || $pageNumber === '0'){
            $pageNumber = 1;
        }

        #endregion

        #region Function execution
            
        $totalPages = $userRepository->getTotalPageNumber(Constants::NUMBER_OF_USERS_PER_PAGE);;

        $users = $userRepository->getUsers($pageNumber, Constants::NUMBER_OF_POSTS_PER_PAGE);

        echo $twig->render('Admin\User\AdminUserList.html.twig', [ 
            'actualPage' => $pageNumber, 
            'totalPages' => $totalPages, 
            'users' => $users, 
            'alert' => ($alert !== false && $alert !== null) ? $alert : '',
            'alertMessage' => ($alertType !== false && $alertType !== null) ? Alert::getMessage($alertType) : '',
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}