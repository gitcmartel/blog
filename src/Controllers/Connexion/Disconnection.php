<?php

namespace Application\Controllers\Connexion;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;

class Disconnection
{
    public function execute()
    {
        if(isset($_SESSION['userId']) && isset($_SESSION['activeUser'])){
            unset($_SESSION["userId"]);
            unset($_SESSION["activeUser"]);
            unset($_SESSION["activeUserFunction"]);
            header("Location:index.php?action=Home\Home");
        }
    }
}