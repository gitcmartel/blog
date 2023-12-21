<?php

namespace Application\Controllers\Password;

use Application\Models\UserRepository;
use Application\Lib\Password;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class PasswordRenewalSubscription
{
    #region Functions
    /**
     * Controller main function
     */
    public function execute(): void
    {
        #region Variables

        $errorMessage = "";
        $user = "";
        $userRepository = new UserRepository();
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
        $token = filter_input(INPUT_GET, 'token', FILTER_DEFAULT);

        if ($email === false || $email === null || $token === false || $token === null) {
            TwigWarning::display(
                "Ce lien n'est plus valide, veuillez générer une nouvelle demande de renouvellement de mot de passe.",
                "index.php?action=Password\PasswordRenewal",
                "Renouveller la demande");
            return;
        }

        //We check if the token and it's date are valid
        $user = $userRepository->getUserFromToken($token, $email);

        if (!$user->tokenCheckValidity()) {
            TwigWarning::display(
                "Ce lien n'est plus valide, veuillez générer une nouvelle demande de renouvellement de mot de passe.",
                "index.php?action=Password\PasswordRenewal",
                "Renouveller la demande");
            return;
        }

        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        $passwordConfirmation = filter_input(INPUT_POST, 'passwordConfirmation', FILTER_DEFAULT);

        //If the user has not already entered his new password
        if ($password === false || $password === null || $passwordConfirmation === false || $passwordConfirmation === null) {
            echo $twig->render('Password\PasswordRenewalSubscription.html.twig', [
                'warningMessage' => '',
                'errorMessage'   => ''
            ]);
            return;
        }

        //Check password validity
        if (!Password::checkPassword($password)) {
            echo $twig->render('Password\PasswordRenewalSubscription.html.twig', [
                'warningMessage' => "Le mot de passe doit être composé d'au moins 8 caractères, 1 majuscule, 1 minuscule, 1 nombre et 1 caractère spécial.",
                'errorMessage'   => $errorMessage
            ]);
            return;
        }

        //Check if the passwords are the same
        if (trim($password) !== trim($passwordConfirmation)) {
            echo $twig->render('Password\PasswordRenewalSubscription.html.twig', [
                'warningMessage' => "Les deux mots de passe ne correspondent pas.",
                'errorMessage'   => $errorMessage
            ]);
            return;
        }

        #endregion

        #region Function execution

        //We update the new password and set the token field and it's date to null
        if ($userRepository->changePassword($user, $password)) {
            header("Location:index.php?action=Home\Home&alert=true&alertType=PasswordChange");
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
//end execute()

