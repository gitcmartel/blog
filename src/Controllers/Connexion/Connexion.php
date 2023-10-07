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
    public function execute() 
    {
        $warningLogin = "";
        $loginValue = "";
        $warningPassword = "";

        if (isset($_POST['login']) && isset($_POST['password'])){
            if(trim($_POST['login'] === "")){
                $warningLogin = "Vous devez entrer un identifiant";
            }

            if(trim($_POST['password'] === "")){
                $warningPassword = "Vous devez entrer un mot de passe";
            }

            $loginValue = $_POST['login'];

            if($warningLogin === "" && $warningPassword === ""){
                //Get the user
                $userRepository = new UserRepository(new DatabaseConnexion);
                $user = $userRepository->getUserByMail($_POST['login']);

                if(isset($user->id)){
                    //Check the password validity
                    if(Password::verify($_POST['password'], $user->password)){
                        $twig = TwigLoader::getEnvironment();
                        
                        $_SESSION['activeUser'] = $user->pseudo;
                        $_SESSION['userId'] = $user->id;

                        echo $twig->render('Connexion/connexionSuccess.html.twig', [
                            'activeUser' => Session::getActiveUser(), 
                            'userFunction' => $user->userFunction
                        ]);
                        return;
                    } else {
                        $warningPassword = "Le mot de passe est incorrect";
                    }
                } else {
                    $warningLogin = "Cet identifiant est inexistant";
                }
            } 
        }

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Connexion/connexion.html.twig', [ 
            'warningLogin' => $warningLogin, 
            'warningPassword' => $warningPassword,
            'loginValue' => $loginValue, 
            'activeUser' => Session::getActiveUser()
        ]);
    }
}