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

        if ($user->getId() !== null) {
            //Check the password validity
            if (Password::verify($password, $user->getPassword())) {

                Session::setUser($user);

                header("Location:index.php?action=Home\Home&alert=true&alertType=Connexion");

                return;
            } else {
                $warningPassword = "Le mot de passe est incorrect";
            }
        } else {
            $warningLogin = "Cet identifiant est inexistant";
        }

        echo $twig->render('Connexion/Connexion.html.twig', [
            'warningLogin' => $warningLogin,
            'warningPassword' => $warningPassword,
            'loginValue' => $loginValue,
            'userFunction' => Session::getActiveUserFunction(),
            'activeUser' => Session::getActiveUser()
        ]);

        #endregion
    }
    #endregion
}
