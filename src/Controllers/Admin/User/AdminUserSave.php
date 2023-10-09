<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Models\DatabaseRepository;
use Application\Lib\Session;
use Application\Lib\Password;
use Application\Lib\TwigLoader;

class AdminUserSave
{
    public function execute()
    {
        $warningGlobal = "";
        $warningLink = "";
        $warningLinkMessage = "";
        $warningName = "";
        $warningSurname = "";
        $warningPseudo = "";
        $warningEmail = "";
        $warningFunction = "";
        $warningValidity = "";
        $warningPassword = "";
        $user = new User();
        $userFunction = "";
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository(new DatabaseConnexion);
            $activeUser = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $activeUser->getUserFunction();
            if($userFunction::Administrator && $activeUser->isValid){
                if(isset($_POST['userId']) && isset($_POST['userName']) && isset($_POST['surname']) && isset($_POST['pseudo']) && isset($_POST['userPwd'])
                && isset($_POST['userPwdConfirmation']) && isset($_POST['userFunction']) && isset($_POST['userValidity'])){
                    //Checks if the fields are corrects
                    if(trim($_POST['userName']) === ""){
                        $warningName = "Vous devez renseigner un prénom";
                        $user->name = "";
                    } else {
                        $user->name = $_POST['userName'];
                    }

                    if(trim($_POST['surname']) === ""){
                        $warningSurname = "Vous devez renseigner un nom";
                        $user->surname = "";
                    } else {
                        $user->surname = $_POST['surname'];
                    }

                    if(trim($_POST['pseudo']) === ""){
                        $warningPseudo = "Vous devez renseigner un pseudo";
                        $user->pseudo = "";
                    } else {
                        $user->pseudo = $_POST['pseudo'];
                    }

                    if(trim($_POST['userMail']) === ""){
                        $warningEmail = "Vous devez renseigner un email";
                        $user->email = "";
                    } else {
                        $emailRegExp = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
                        if ( ! (preg_match($emailRegExp, trim($_POST['userMail'])) === 1)){
                            $warningEmail = "L'adresse email est incorrecte";
                        }
                        $user->email = $_POST['userMail'];
                    }

                    if(trim($_POST['userFunction']) === ""){
                        $warningFunction = "Vous devez renseigner une fonction";
                        $user->userFunction = "";
                    } else {
                        $user->userFunction = $_POST['userFunction'];
                    }

                    if(trim($_POST['userValidity']) === ""){
                        $warningValidity = "Vous devez selectionner une option";
                        $user->isValid = 0;
                    } else {
                        $user->isValid = $_POST['userValidity'];
                    }

                    if((trim($_POST['userPwd']) !== "" || trim($_POST['userPwdConfirmation']) !== "") && 
                        (trim($_POST['userPwd']) !== trim($_POST['userPwdConfirmation']))){
                        $warningPassword = 'Les deux mot de passe ne sont pas identiques';
                        $user->password = "";
                    } else {
                        $user->password = $_POST['userPwd'];
                    }

                    if(! ((trim($_POST['userPwd']) === "") && trim($_POST['userPwdConfirmation']) === "")){
                        if (! Password::checkPassword($_POST['userPwd'])){
                            $warningPassword = "Le mot de passe doit être composé d'au moins 8 caractères, 1 majuscule, 1 minuscule, 1 nombre et 1 caractère spécial";
                            $user->password = "";
                        } else {
                            $user->password = $_POST['userPwd'];
                        }  
                    }

                    //If it is a new user the password fields cannot be empty
                    if(((trim($_POST['userPwd']) === "") && trim($_POST['userPwdConfirmation']) === "") && trim($_POST['userId']) === ""){
                        $warningPassword = "Vous devez renseigner un mot de passe";
                        $user->password = "";
                    } else {
                        $user->password = $_POST['userPwd'];
                    }  

                    //Set the user id if there is one. If there is a warning message, then the user id
                    //will get back into the input form field
                    if (trim($_POST['userId']) !== ""){
                        $user->id = $_POST['userId'];
                    }

                    if($warningName === "" && $warningSurname === "" && $warningPseudo === "" && $warningEmail === "" 
                    & $warningPassword === "" & $warningFunction === "" & $warningValidity === ""){
                        if (trim($_POST['userId'] !== "")){
                            //If there is a userId we update
                            $user->id = $_POST['userId'];
                            if ($userRepository->updateUser($user)) {
                                //We display the updated user list
                                header("Location:index.php?action=Admin\User\AdminUserList&pageNumber=1");
                                return;
                            } else {
                                $warningGlobal = "Une erreur est survenue lors de l'enregistrement des données";
                            }
                        } else { //If there is no userId we create a new user
                            if ($userRepository->createUser($user)){
                                //We display the updated user list
                                header("Location:index.php?action=Admin\User\AdminUserList&pageNumber=1");
                                return;
                            } else {
                                $warningGlobal = "Une erreur est survenue lors de l'enregistrement des données";
                            }
                        }
                    }
                }
            } else {
                $warningGlobal = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
            }
        } else {
            $warningGlobal = "Veuillez-vous identifier pour pouvoir accéder à cette page";
        }

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Admin\User\User.html.twig', [ 
            'warningGlobal' => $warningGlobal, 
            'warningName' => $warningName, 
            'warningSurname' => $warningSurname,
            'warningPseudo' => $warningPseudo,
            'warningEmail' => $warningEmail, 
            'warningPassword' => $warningPassword, 
            'warningFunction' => $warningFunction, 
            'warningValidity' => $warningValidity, 
            'user' => $user,
            'userFunction' => $userFunction,
            'activeUser' => Session::getActiveUser()
        ]);
    }
}