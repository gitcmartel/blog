<?php

namespace Application\Controllers\Connexion;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Password;
use Application\Lib\Session;
use Application\Lib\TwigLoader;

class Connexion 
{
    #region Functions
    public function execute() 
    {
        #region Variables

        $warningLogin = "";
        $warningPassword = "";
        $loginValue = "";
        $userRepository = new UserRepository(new DatabaseConnexion);
        $user = "";
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        if (! isset($_POST['login']) && ! isset($_POST['password'])){
            echo $twig->render('Connexion/connexion.html.twig', [ 
                'warningLogin' => $warningLogin, 
                'warningPassword' => $warningPassword,
                'loginValue' => $loginValue,
                'userFunction' => Session::getActiveUserFunction(),
                'activeUser' => Session::getActiveUser()
            ]);
            return;
        }

        if(trim($_POST['login'] === "")){
            $warningLogin = "Vous devez entrer un identifiant";
        }

        if(trim($_POST['password'] === "")){
            $warningPassword = "Vous devez entrer un mot de passe";
        }

        $loginValue = $_POST['login'];

        if($warningLogin !== "" || $warningPassword !== ""){
            echo $twig->render('Connexion/connexion.html.twig', [ 
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

        $user = $userRepository->getUserByMail($_POST['login']);

        if($user->getId() !== null){
            //Check the password validity
            if(Password::verify($_POST['password'], $user->getPassword())){
                
                $_SESSION['activeUser'] = $user->getPseudo();
                $_SESSION['activeUserFunction'] = $user->getUserFunction()->toString();
                $_SESSION['userId'] = $user->getId();

                echo $twig->render('Connexion/connexionSuccess.html.twig', [
                    'activeUser' => Session::getActiveUser(), 
                    'userFunction' => Session::getActiveUserFunction()
                ]);
                return;
            } else {
                $warningPassword = "Le mot de passe est incorrect";
            }
        } else {
            $warningLogin = "Cet identifiant est inexistant";
        }

        echo $twig->render('Connexion/connexion.html.twig', [ 
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