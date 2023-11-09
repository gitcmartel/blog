<?php

namespace Application\Lib;

use Application\Lib\DatabaseConnexion;
use Application\Models\UserRepository;
use Application\Models\User;

class UserActiveCheckValidity
{
    #region Functions
    static function check(array $functions) : bool
    {
        #region Variables

        $userRepository = new UserRepository();
        $user = "";

        #endregion

        #region Tests conditions
        if(! isset($_SESSION['userId'])){
            return false;
        }

        #endregion

        #region Function execution

        $user = $userRepository->getUser($_SESSION['userId']);

        if($user->getId() == null){
            return false;
        } else {
            return $user->checkValidity($functions);
        }

        #endregion
    }
   #endregion
}