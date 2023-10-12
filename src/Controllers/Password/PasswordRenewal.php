<?php

namespace Application\Controllers\Password;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Password;
use Application\Lib\Email;
use Application\Lib\Constants;
use Application\Lib\DatabaseConnexion;
use Application\Lib\TwigLoader;
use DateTime;

class PasswordRenewal
{
    public function execute()
    {

        $warningEmail = "";
        $successMessage = "";
        $errorMessage = "";
        $emailValue = "";

        if(isset($_POST['emailAddress'])){
            if(trim($_POST['emailAddress'] === "")){
                $warningEmail = "Vous devez entrer une addresse email";
            }

            $emailValue = $_POST['emailAddress'];

            if($warningEmail === ""){
                //Get the user
                $userRepository = new UserRepository(new DatabaseConnexion);
                $user = $userRepository->getUserByMail($_POST['emailAddress']);

                if(isset($user->id)){
                    //Generates the token
                    $token = Password::generateToken();
                    //Stores the token and the actual datetime in the user table
                    if ($userRepository->updateToken($user->id, $token)){
                        //Send the email with the password renewal link
                        $loader = new \Twig\Loader\FilesystemLoader('templates');
                        $twig = new \Twig\Environment($loader);

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
                        $twig->render('mailMessage.html.twig', $parameters)
                        );

                        if($email->sendMail()){
                            $successMessage = "Un mail vous a été envoyé pour le renouvellement de votre mot de passe";
                        } else {
                            $errorMessage = 'Erreur de Mailer : ' . $email->errorInfo;
                        }
                    }
                } else {
                    $errorMessage = "Cette adresse email est inconnue";
                }
            }
        }

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Password\PasswordRenewal.html.twig', [ 
            'warningLogin' => $warningEmail, 
            'emailValue' => $emailValue,
            'errorMessage' =>$errorMessage,
            'successMessage' => $successMessage, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);
    }
}