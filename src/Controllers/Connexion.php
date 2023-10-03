<?php

namespace Application\Controllers;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Password;
use Application\Lib\Session;

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
                        $loader = new \Twig\Loader\FilesystemLoader('templates');
                        $twig = new \Twig\Environment($loader);
                        
                        $_SESSION['activeUser'] = $user->pseudo;
                        $_SESSION['userId'] = $user->id;

                        echo $twig->render('connexionSuccess.twig', [
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

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        
        echo $twig->render('connexion.twig', [ 
            'warningLogin' => $warningLogin, 
            'warningPassword' => $warningPassword,
            'loginValue' => $loginValue, 
            'activeUser' => Session::getActiveUser()
        ]);
    }
}