<?php 


namespace Application\Controllers\Subscription;

use Application\Models\User;
use Application\Models\UserRepository;
use Application\Lib\Password;
use Application\Lib\Session;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;

use DateTime;

class Subscription 
{
    #region Functions
    public function execute()
    {
        #region Variables

        $warningEmail = array("fieldContent" => "", "warningMessage" => "");
        $warningPseudo = array("fieldContent" => "", "warningMessage" => "");
        $warningPassword = array("fieldContent" => "", "warningMessage" => "");
        $warningName = array("fieldContent" => "", "warningMessage" => "");
        $warningSurname = array("fieldContent" => "", "warningMessage" => "");
        $warningGeneral = array("fieldContent" => "", "warningMessage" => "");

        $twig = TwigLoader::getEnvironment();
        $userRepository = new UserRepository(new DatabaseConnexion);
        $user = new User();

        #endregion 

        #region Conditions tests

        //Checks if a new account creation has been sent
        if (! (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['pseudo']) && isset($_POST['email']) 
        && isset($_POST['password']) && isset($_POST['passwordConfirmation']))) {
            echo $twig->render('Subscription\Subscription.html.twig', [
                'warningName' => $warningName, 
                'warningSurname' => $warningSurname, 
                'warningEmail' => $warningEmail, 
                'warningPseudo' => $warningPseudo, 
                'warningPassword' => $warningPassword, 
                'warningGeneral' => $warningGeneral, 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => Session::getActiveUserFunction()
            ]);  
            return;
        }

        //Fields content controls
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

        //If there is a warning to display (incorrect field content)
        if (! ($warningEmail["warningMessage"] === "" && $warningPseudo["warningMessage"] === "" && $warningPassword["warningMessage"] === "" 
        && $warningName["warningMessage"] === "" && $warningSurname["warningMessage"] === "")){
            echo $twig->render('Subscription\Subscription.html.twig', [
                'warningName' => $warningName, 
                'warningSurname' => $warningSurname, 
                'warningEmail' => $warningEmail, 
                'warningPseudo' => $warningPseudo, 
                'warningPassword' => $warningPassword, 
                'warningGeneral' => $warningGeneral, 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => Session::getActiveUserFunction()
            ]);  
            return;
        }

        #endregion

        #region Function execution

        $user->constructWithParameters($_POST['name'], $_POST['surname'], $_POST['pseudo'], $_POST['email'], 
        $_POST['password'], Date("Y-m-d H:i:s"), "Lecteur", 0);
        if($userRepository->createUser($user)){
            echo $twig->render('Subscription\SubscriptionSuccess.html.twig', [
                'activeUser' => Session::getActiveUser()
            ]);  
        } else {
            TwigWarning::display(
                "Un problème est survenu lors de la création du compte.", 
                "index.php?action=Subscription\Subscription", 
                "Réessayer");
        }

        #endregion
    }
    #endregion
}