<?php

namespace Application\Controllers\Admin;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Session;

class AdminUserSave
{
    public function execute()
    {
        $warningGeneral = "";
        $warningLink = "";
        $warningLinkMessage = "";
        $warningName = "";
        $warningSurname = "";
        $warningPseudo = "";
        $warningEmail = "";
        $postName = "";
        $postSurname = "";
        $postPseudo = "";
        $postEmail = "";
        $userFunction = "";
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository();
            $activeUser = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $activeUser->userFunction;
            if($activeUser->isAdmin() && $activeUser->isValid){
                if (isset($_POST['userId'])){
                    if(isset($_POST['userName']) && isset($_POST['surname']) && isset($_POST['pseudo']) && isset($_POST['userPwd'])
                    && isset($_POST['userPwdConfirmation']) && isset($_POST['userFunction']) && isset($_POST['userValidity'])){
                        //Checks if the fields are corrects
                        if(trim($_POST['userName']) === ""){
                            $warningName = "Vous devez renseigner un prénom";
                        } else {
                            $postName = $_POST['userName'];
                        }

                        if(trim($_POST['surname']) === ""){
                            $warningSurname = "Vous devez renseigner un nom";
                        } else {
                            $postSurname = $_POST['surname'];
                        }

                        if(trim($_POST['pseudo']) === ""){
                            $warningPseudo = "Vous devez renseigner un pseudo";
                        } else {
                            $postPseudo = $_POST['pseudo'];
                        }

                        if(trim($_POST['userMail']) === ""){
                            $warningEmail = "Vous devez renseigner un email";
                        } else {
                            $postEmail = $_POST['userMail'];
                        }
                    }
                }
            } else {
                $warningGeneral = "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site";
                $warningLink = "index.php/action=Home";
                $warningLinkMessage = "Nous contacter";
            }
        } else {
            $warningGeneral = "Veuillez-vous identifier pour pouvoir accéder à cette page";
            $warningLink = "index.php?action=Connexion";
            $warningLinkMessage = "Se connecter";
        }
    }
}