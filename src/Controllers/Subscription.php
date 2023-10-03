<?php 


namespace Application\Controllers;

use Application\Models\User;
use Application\Models\UserRepository;
use Application\Lib\Password;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use DateTime;

class Subscription 
{
    public function execute()
    {
        $warningEmail = array("fieldContent" => "", "warningMessage" => "");
        $warningPseudo = array("fieldContent" => "", "warningMessage" => "");
        $warningPassword = array("fieldContent" => "", "warningMessage" => "");
        $warningName = array("fieldContent" => "", "warningMessage" => "");
        $warningSurname = array("fieldContent" => "", "warningMessage" => "");
        $warningGeneral = array("fieldContent" => "", "warningMessage" => "");

        //Checks if a new account creation has been sent
        if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['pseudo']) && isset($_POST['email']) 
        && isset($_POST['password']) && isset($_POST['passwordConfirmation'])) {

            $userRepository = new UserRepository(new DatabaseConnexion);
            
            if ($userRepository->exists($_POST['email'], 'email')) {
                $warningEmail["warningMessage"] = "Cette adresse email existe déjà";
            }

            if ( trim($_POST['email']) === "") {
                $warningEmail["warningMessage"] = "Le champ email doit être complété";
            }

            if ($userRepository->checkEmail(trim($_POST['email'])) !== true) {
                $warningEmail["warningMessage"] = "L'adresse email est incorrecte";
            }

            if ($userRepository->exists($_POST['pseudo'], 'pseudo')) {
                $warningPseudo["warningMessage"] = "Ce pseudo existe déjà";
            }

            if ( trim($_POST['pseudo']) === "") {
                $warningPseudo["warningMessage"] = "Le champ pseudo doit être complété";
            }

            if (! Password::checkPassword($_POST['password'])){
                $warningPassword["warningMessage"] = "Le mot de passe doit être composé d'au moins 8 caractères, 1 majuscule, 1 minuscule, 1 nombre et 1 caractère spécial";
            }

            if ($_POST['password'] !== $_POST['passwordConfirmation']){
                $warningPassword["warningMessage"] = "Les deux mots de passe ne correspondent pas";
            }

            if (! $userRepository->checkNameSurname($_POST['name'])){
                $warningName["warningMessage"] = "Le champ prénom doit être complété (50 caractères max)";
            }

            if (! $userRepository->checkNameSurname($_POST['surname'])){
                $warningSurname["warningMessage"] = "Le champ nom doit être complété (50 caractères max)";
            }

            $warningEmail["fieldContent"] = $_POST['email'];
            $warningPseudo["fieldContent"] = $_POST['pseudo'];
            $warningPassword["fieldContent"] = $_POST['password'];
            $warningName["fieldContent"] = $_POST['name'];
            $warningSurname["fieldContent"] = $_POST['surname'];

            //If the email and pseudo are available we create the user account
            if ($warningEmail["warningMessage"] === "" && $warningPseudo["warningMessage"] === "" && $warningPassword["warningMessage"] === "" 
            && $warningName["warningMessage"] === "" && $warningSurname["warningMessage"] === ""){
                $user = new User();
                $user->constructWhithParameters($_POST['name'], $_POST['surname'], $_POST['pseudo'], $_POST['email'], 
                $_POST['password'], DateTime::createFromFormat("Y-m-d H:i:s", Date("Y-m-d H:i:s")), "Lecteur", 0);
                if($userRepository->createUser($user)){
                    $loader = new \Twig\Loader\FilesystemLoader('templates');
                    $twig = new \Twig\Environment($loader);
                    echo $twig->render('subscriptionSuccess.twig', [
                        'activeUser' => Session::getActiveUser()
                    ]);  
                    return;
                } else {
                    $warningGeneral = "Un problème est survenu lors de la création du compte";
                }
            }
        }

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);

        echo $twig->render('subscription.twig', [
            'warningName' => $warningName, 
            'warningSurname' => $warningSurname, 
            'warningEmail' => $warningEmail, 
            'warningPseudo' => $warningPseudo, 
            'warningPassword' => $warningPassword, 
            'warningGeneral' => $warningGeneral, 
            'activeUser' => Session::getActiveUser()
        ]);  
        return; 
    }
}