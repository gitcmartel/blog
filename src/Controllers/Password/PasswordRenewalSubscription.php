<?php

namespace Application\Controllers\Password;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Password;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;
use DateTime;

class PasswordRenewalSubscription
{
    #region Functions
    public function execute()
    {
        #region Variables

        $errorMessage = "";
        $warningMessage = "";
        $user = "";
        $userRepository = new UserRepository();
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        if(! isset($_GET['token']) || ! isset($_GET['email'])){
            TwigWarning::display(
                "Ce lien n'est plus valide, veuillez générer une nouvelle demande de renouvellement de mot de passe.", 
                "index.php?action=Password\PasswordRenewal", 
                "Renouveller la demande");
            return; 
        }

        //We check if the token and it's date are valid
        $user = $userRepository->getUserFromToken($_GET['token'], $_GET['email']);

        if(! $user->tokenCheckValidity()) {
            TwigWarning::display(
                "Ce lien n'est plus valide, veuillez générer une nouvelle demande de renouvellement de mot de passe.", 
                "index.php?action=Password\PasswordRenewal", 
                "Renouveller la demande");
            return; 
        }

        //If the user has not already entered his new password
        if(! isset($_POST['password']) && ! isset($_POST['passwordConfirmation'])){
            echo $twig->render('Password\PasswordRenewalSubscription.html.twig', [
                'warningMessage' => '', 
                'errorMessage' => ''
            ]);
            return;
        }

        //Check password validity
        if (! Password::checkPassword($_POST['password'])){
            echo $twig->render('Password\PasswordRenewalSubscription.html.twig', [
                'warningMessage' => "Le mot de passe doit être composé d'au moins 8 caractères, 1 majuscule, 1 minuscule, 1 nombre et 1 caractère spécial.", 
                'errorMessage' => $errorMessage
            ]);
            return;
        }

        //Check if the passwords are the same
        if(trim($_POST['password']) !== trim($_POST['passwordConfirmation'])){
            echo $twig->render('Password\PasswordRenewalSubscription.html.twig', [
                'warningMessage' => "Les deux mots de passe ne correspondent pas.", 
                'errorMessage' => $errorMessage
            ]);
            return;
        }

        #endregion

        #region Function execution

        //We update the new password and set the token field and it's date to null
        if($userRepository->changePassword($user->getId(), $_POST['password'])){
            echo $twig->render('Password\PasswordRenewalSuccess.html.twig', []);
        } else {
            TwigWarning::display(
                "Une erreur est survenue lors de la tentative de renouvellement du mot de passe.", 
                "index.php?action=Password\PasswordRenewal", 
                "Renouveller la demande");
        }

        #endregion
    }

    #endregion
}