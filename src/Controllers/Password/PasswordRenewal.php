<?php

namespace Application\Controllers\Password;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Password;
use Application\Lib\Email;
use Application\Lib\Constants;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;
use DateTime;

class PasswordRenewal
{
    #region Functions
    public function execute()
    {
        #region Variables

        $successMessage = "";
        $errorMessage = "";
        $emailValue = "";
        $userRepository = new UserRepository();
        $user = "";
        $twig = TwigLoader::getEnvironment();

        #endregion

        #region Conditions tests

        //If the emailAdress field is not set we display the page
        if(! isset($_POST['emailAddress'])){
            echo $twig->render('Password\PasswordRenewal.html.twig', [ 
                'warningLogin' => "", 
                'emailValue' => $emailValue,
                'errorMessage' =>$errorMessage,
                'successMessage' => $successMessage, 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => Session::getActiveUserFunction()
            ]);
            return;
        }

        if(trim($_POST['emailAddress'] === "")){
            echo $twig->render('Password\PasswordRenewal.html.twig', [ 
                'warningLogin' => "Vous devez renseigner une adresse email.", 
                'emailValue' => $emailValue,
                'errorMessage' =>$errorMessage,
                'successMessage' => $successMessage, 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => Session::getActiveUserFunction()
            ]);
            return;
        }

        $emailValue = $_POST['emailAddress'];
        $user = $userRepository->getUserByMail($_POST['emailAddress']);

        if($user->getId() === null){
            echo $twig->render('Password\PasswordRenewal.html.twig', [ 
                'warningLogin' => "Cette addresse email est inconue.", 
                'emailValue' => $emailValue,
                'errorMessage' =>$errorMessage,
                'successMessage' => $successMessage, 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => Session::getActiveUserFunction()
            ]);
            return;
        }

        $token = Password::generateToken();

        //Stores the token and the actual datetime in the user table
        if (! $userRepository->updateToken($user, $token)){
            TwigWarning::display(
                "Un problème est survenu lors de la demande de renouvellement de mot de passe.", 
                "index.php?action=Password\PasswordRenewal", 
                "Réessayer");
            return; 
        }

        #endregion

        #region Function execution

        //Send the email with the password renewal link
        $parameters = array(
            'domaine' => Constants::WEBSITE_DOMAIN, 
            'emailAdress' => $_POST['emailAddress'],
            'token' => $token
        );

        $email = new Email("Blog Devcm", 
            "", 
            "contact@blog.devcm.fr", 
            $_POST['emailAddress'],
            "Renouvellement de mot de passe", 
            $twig->render('Mail\mailMessage.html.twig', $parameters)
        );

        if($email->sendMail()){
            $successMessage = "Un mail vous a été envoyé pour le renouvellement de votre mot de passe";
        } else {
            $errorMessage = 'Erreur de Mailer : ' . $email->errorInfo;
        }

        echo $twig->render('Password\PasswordRenewal.html.twig', [ 
            'warningLogin' => "", 
            'emailValue' => $emailValue,
            'errorMessage' =>$errorMessage,
            'successMessage' => $successMessage, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}