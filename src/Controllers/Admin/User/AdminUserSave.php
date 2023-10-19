<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;
use Application\Lib\Password;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class AdminUserSave
{
    #region Functions
    public function execute()
    {
        #region Variables

        $warningName = "";
        $warningSurname = "";
        $warningPseudo = "";
        $warningEmail = "";
        $warningFunction = "";
        $warningValidity = "";
        $warningPassword = "";
        $userRepository = new UserRepository(new DatabaseConnexion);
        $user = new User();
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        if(! UserActiveCheckValidity::check(array('Administrateur'))
            || ! isset($_POST['userId']) || ! isset($_POST['userName']) || ! isset($_POST['surname']) || ! isset($_POST['pseudo']) || ! isset($_POST['userPwd'])
            || ! isset($_POST['userPwdConfirmation']) || ! isset($_POST['userFunction']) || ! isset($_POST['userValidity'])){
                TwigWarning::display(
                    "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                    "index.php/action=Home\Home", 
                    "Nous contacter");
                return; 
        }

        //Checks if the fields are corrects
        if(trim($_POST['userName']) === ""){
            $warningName = "Vous devez renseigner un prénom";
        } else {
            $user->setName($_POST['userName']);
        }

        if(trim($_POST['surname']) === ""){
            $warningSurname = "Vous devez renseigner un nom";
        } else {
            $user->setSurname($_POST['surname']);
        }

        if(trim($_POST['pseudo']) === ""){
            $warningPseudo = "Vous devez renseigner un pseudo";
        } else {
            $user->setPseudo($_POST['pseudo']);
        }

        if(trim($_POST['userMail']) === ""){
            $warningEmail = "Vous devez renseigner un email";
        } else {
            $emailRegExp = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
            if ( ! (preg_match($emailRegExp, trim($_POST['userMail'])) === 1)){
                $warningEmail = "L'adresse email est incorrecte";
            }
            $user->setEmail($_POST['userMail']);
        }

        if(trim($_POST['userFunction']) === ""){
            $warningFunction = "Vous devez renseigner une fonction";
        } else {
            $user->setUserFunction($_POST['userFunction']);
        }

        if(trim($_POST['userValidity']) === ""){
            $warningValidity = "Vous devez selectionner une option";
        } else {
            $user->setIsValid($_POST['userValidity']);
        }

        if((trim($_POST['userPwd']) !== "" || trim($_POST['userPwdConfirmation']) !== "") && 
            (trim($_POST['userPwd']) !== trim($_POST['userPwdConfirmation']))){
            $warningPassword = 'Les deux mot de passe ne sont pas identiques';
        } else {
            $user->setPassword($_POST['userPwd']);
        }

        if(! ((trim($_POST['userPwd']) === "") && trim($_POST['userPwdConfirmation']) === "")){
            if (! Password::checkPassword($_POST['userPwd'])){
                $warningPassword = "Le mot de passe doit être composé d'au moins 8 caractères, 1 majuscule, 1 minuscule, 1 nombre et 1 caractère spécial";
            } else {
                $user->setPassword($_POST['userPwd']);
            }  
        }

        //If it is a new user the password fields cannot be empty
        if(((trim($_POST['userPwd']) === "") && trim($_POST['userPwdConfirmation']) === "") && trim($_POST['userId']) === ""){
            $warningPassword = "Vous devez renseigner un mot de passe";
        } else {
            $user->setPassword($_POST['userPwd']);
        }

        //Set the user id if there is one. If there is a warning message, then the user id
        //will get back into the input form field
        if (trim($_POST['userId']) !== ""){
            $user->setId($_POST['userId']);
        }

        //If there is an incorrect field we display the error message
        if($warningName !== "" || $warningSurname !== "" || $warningPseudo !== "" || $warningEmail !== "" || 
         $warningPassword !== "" || $warningFunction !== "" || $warningValidity !== ""){
            echo $twig->render('Admin\User\User.html.twig', [ 
                'warningName' => $warningName, 
                'warningSurname' => $warningSurname,
                'warningPseudo' => $warningPseudo,
                'warningEmail' => $warningEmail, 
                'warningPassword' => $warningPassword, 
                'warningFunction' => $warningFunction, 
                'warningValidity' => $warningValidity, 
                'user' => $user,
                'userFunction' => Session::getActiveUserFunction(),
                'activeUser' => Session::getActiveUser()
            ]);
        }

        #endregion

        #region Function execution

        if (trim($_POST['userId'] !== "")){
            //If there is a userId we update
            $user->setId($_POST['userId']);
            if ($userRepository->updateUser($user)) {
                //We display the updated user list
                header("Location:index.php?action=Admin\User\AdminUserList&pageNumber=1");
                return;
            } else {
                TwigWarning::display(
                    "Un problème est survenu lors de l'enregistrement de l'utilisateur.", 
                    "index.php/action=Home\Home", 
                    "Retour à l'accueil");
                return; 
            }
        } else { //If there is no userId we create a new user
            if ($userRepository->createUser($user)){
                //We display the updated user list
                header("Location:index.php?action=Admin\User\AdminUserList&pageNumber=1");
                return;
            } else {
                TwigWarning::display(
                    "Un problème est survenu lors de l'enregistrement de l'utilisateur.", 
                    "index.php/action=Home\Home", 
                    "Retour à l'accueil");
                return; 
            }
        }

        #endregion
    }
    #endregion
}