<?php

namespace Application\Lib;

use Application\Models\UserRepository;

class UserActiveCheckValidity
{
    #region Functions
    static function check(array $functions) : bool
    {
        #region Variables

        $userRepository = new UserRepository();
        $user = "";

        #endregion
        $userId = Session::getActiveUserId();

        #region Tests conditions
        if($userId === null){
            return false;
        }

        #endregion

        #region Function execution

        $user = $userRepository->getUser($userId);

        if($user->getId() == null){
            return false;
        } else {
            return $user->checkValidity($functions);
        }

        #endregion
    }
   #endregion
}