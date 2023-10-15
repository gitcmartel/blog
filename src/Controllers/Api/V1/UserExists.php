<?php

namespace Application\Controllers\Api\V1;

use Application\Lib\DatabaseConnexion;
use Application\Models\UserRepository;

class UserExists 
{
    #region Functions
    public function execute()
    {   
        $response = array("email" => false, "pseudo" => false);
        if (isset($_GET['email']) && isset($_GET['pseudo'])) {    
            if ($_GET['email'] !== "" && $_GET['pseudo'] !== ""){
                $userRepository = new UserRepository();
                $response['email'] = $userRepository->exists($_GET['email'], 'email');
                $response['pseudo'] = $userRepository->exists($_GET['pseudo'], 'pseudo');;
            }
        }

        echo json_encode($response);
    }
    #endregion
}