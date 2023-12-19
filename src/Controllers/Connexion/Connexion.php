<?php

namespace Application\Controllers\Connexion;

use Application\Models\UserRepository;
use Application\Lib\Password;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class Connexion
{
    #region Functions
    /**
     * Controller main function execute
     */
    public function execute()
    {
        #region Variables

        $warningLogin = "";
        $warningPassword = "";
        $loginValue = "";
        $userRepository = new UserRepository();
        $user = "";
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

        if ($login === false || $login === null || $password === false || $password === null) {
            echo $twig->render('Connexion/Connexion.html.twig', [
                'warningLogin' => $warningLogin,
                'warningPassword' => $warningPassword,
                'loginValue' => $loginValue,
                'userFunction' => Session::getActiveUserFunction(),
                'activeUser' => Session::getActiveUser()
            ]);
            return;
        }

        if (trim($login === "")) {
            $warningLogin = "Vous devez entrer un identifiant";
        }

        if (trim($password === "")) {
            $warningPassword = "Vous devez entrer un mot de passe";
        }

        $loginValue = $login;

        if ($warningLogin !== "" || $warningPassword !== "") {
            echo $twig->render('Connexion/Connexion.html.twig', [
                'warningLogin' => $warningLogin,
                'warningPassword' => $warningPassword,
                'loginValue' => $loginValue,
                'userFunction' => Session::getActiveUserFunction(),
                'activeUser' => Session::getActiveUser()
            ]);
            return;
        }

        #endregion

        #region Function execution

        $user = $userRepository->getUserByMail($login);


        if($user->getIsValid() === false) {
            $warningLogin = 'Cet identifiant est inactif ! Contactez l\'administrateur du site.';
        }

        //Check the password validity
        if (Password::verify($password, $user->getPassword()) === false) {
            $warningPassword = 'Le mot de passe est incorrect';
        }

        if ($user->getId() === null) {
            $warningLogin = 'Adresse email inconnue !';
        }

        if($warningLogin !== "" || $warningPassword !== "") {
            echo $twig->render('Connexion/Connexion.html.twig', [
                'warningLogin' => $warningLogin,
                'warningPassword' => $warningPassword,
                'loginValue' => $loginValue,
                'userFunction' => Session::getActiveUserFunction(),
                'activeUser' => Session::getActiveUser()
            ]);
            return;
        }

        Session::setUser($user);
        
        header("Location:index.php?action=Home\Home&alert=true&alertType=Connexion");

        #endregion
    }
    #endregion
}
