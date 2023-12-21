<?php

namespace Application\Controllers\Connexion;


class Disconnection
{
    #region Functions
    /**
     * Controller main function
     */
    public function execute(): void
    {
        #region Function execution
        
        if(isset($_SESSION['userId']) && isset($_SESSION['activeUser'])){
            unset($_SESSION["userId"]);
            unset($_SESSION["activeUser"]);
            unset($_SESSION["activeUserFunction"]);
            header("Location:index.php?action=Home\Home&alert=true&alertType=Disconnexion");
        }

        #endregion
    }
    #endregion
}
//end execute()
