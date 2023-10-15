<?php

namespace Application\Lib;

class TwigWarning
{
    #region functions 

    //Display the warning page
    public static function display(string $warninMessage, string $warningLink, string $warningLinkMessage)
    {
        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Warning\NotAllowed.html.twig', [ 
            'warningMessage' => $warningGeneral, 
            'warningLink' => $warningLink, 
            'warningLinkMessage' => $warningLinkMessage,
            'userFunction' => Session::getActiveUserFunction(),
            'activeUser' => Session::getActiveUser()
        ]);
    }
    #endregion
}