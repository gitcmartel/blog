<?php

namespace Application\Models;

use Application\Lib\DatabaseConnexion;

class UserActiveCheckValidity
{
    static function Check(array $functions) : bool
    {
        $userRepository = new UserRepository(new DatabaseConnexion);
        $user = $userRepository->getUser($_SESSION['userId']);
        //Checks if the activeUserFunction session variable is set
        if(isset($_SESSION['activeUserFunction'])){
            return $user->checkValidity($functions);
        } else if(isset($_SESSION['userId'])){ //Else checks if the userId session variable is set
            return $user->checkValidity($functions);
        }else {
            return false;
        }
    }
}