<?php

namespace Application\Lib;

use Application\Lib\DatabaseConnexion;

class UserActiveCheckValidity
{
    #region Functions
    static function check(array $functions) : bool
    {
        $userRepository = new UserRepository(new DatabaseConnexion);
        $user = $userRepository->getUser($_SESSION['userId']);

        if($user->getId() == null){
            return false;
        } else {
            return $user->checkValidity($functions);
        }
    }
   #endregion
}