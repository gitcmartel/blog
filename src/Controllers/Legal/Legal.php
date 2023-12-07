<?php

namespace Application\Controllers\Legal;

use Application\Lib\Session;
use Application\Lib\TwigLoader;

class Legal 
{
    public function execute() {

        #region Function execution

        $twig = TwigLoader::getEnvironment();

        echo $twig->render('Legal\LegalNotice.html.twig', [ 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
}