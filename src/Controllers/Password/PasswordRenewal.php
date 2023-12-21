<?php

namespace Application\Controllers\Password;

use Application\Models\UserRepository;
use Application\Lib\Password;
use Application\Lib\Email;
use Application\Lib\Constants;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;

class PasswordRenewal
{
    #region Functions
    /**
     * Controller main function
     */
    public function execute(): void
    {
        #region Variables

        $successMessage = "";
        $errorMessage = "";
        $userRepository = new UserRepository();
        $user = "";
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        $emailAdress = filter_input(INPUT_POST, 'emailAddress', FILTER_SANITIZE_EMAIL);

        //If the emailAdress field is not set we display the page
        if ($emailAdress === false || $emailAdress === null) {
            echo $twig->render('Password\PasswordRenewal.html.twig', [
                'warningLogin'   => '',
                'emailValue'     => '',
                'errorMessage'   => $errorMessage,
                'successMessage' => $successMessage,
                'activeUser'     => Session::getActiveUser(),
                'userFunction'   => Session::getActiveUserFunction()
            ]);
            return;
        }

        if (trim($emailAdress) === "") {
            echo $twig->render('Password\PasswordRenewal.html.twig', [
                'warningLogin'   => "Vous devez renseigner une adresse email.",
                'emailValue'     => '',
                'errorMessage'   => $errorMessage,
                'successMessage' => $successMessage,
                'activeUser'     => Session::getActiveUser(),
                'userFunction'   => Session::getActiveUserFunction()
            ]);
            return;
        }

        $user = $userRepository->getUserByMail($emailAdress);

        if ($user->getId() === null) {
            echo $twig->render('Password\PasswordRenewal.html.twig', [
                'warningLogin'   => "Cette addresse email est inconue.",
                'emailValue'     => $emailAdress,
                'errorMessage'   => $errorMessage,
                'successMessage' => $successMessage,
                'activeUser'     => Session::getActiveUser(),
                'userFunction'   => Session::getActiveUserFunction()
            ]);
            return;
        }

        $token = Password::generateToken();

        //Stores the token and the actual datetime in the user table
        if (!$userRepository->updateToken($user, $token)) {
            TwigWarning::display(
                "Un problème est survenu lors de la demande de renouvellement de mot de passe.",
                "index.php?action=Password\PasswordRenewal",
                "Réessayer");
            return;
        }

        #endregion

        #region Function execution

        //Send the email with the password renewal link
        $parameters = [
            'domaine'     => Constants::WEBSITE_DOMAIN,
            'emailAdress' => $emailAdress,
            'token'       => $token
        ];

        $email = new Email("Blog Devcm",
            "",
            "contact@blog.devcm.fr",
            $emailAdress,
            "Renouvellement de mot de passe",
            $twig->render('Mail\MailMessage.html.twig', $parameters)
        );

        if ($email->sendMail()) {
            $successMessage = "Un mail vous a été envoyé pour le renouvellement de votre mot de passe";
        } else {
            $errorMessage = 'Erreur de Mailer : ' . $email->getErrorInfo();
        }

        echo $twig->render('Password\PasswordRenewal.html.twig', [
            'warningLogin'   => "",
            'emailValue'     => $emailAdress,
            'errorMessage'   => $errorMessage,
            'successMessage' => $successMessage,
            'activeUser'     => Session::getActiveUser(),
            'userFunction'   => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}
//end execute()

