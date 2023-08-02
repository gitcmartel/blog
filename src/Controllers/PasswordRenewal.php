<?php

namespace Application\Controllers;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\Password;
use Application\Lib\Email;
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
                $userRepository = new UserRepository();
                $user = $userRepository->getUserByMail($_POST['emailAddress']);

                if(isset($user->id)){
                    //Generates the token
                    $token = Password::generateToken();
                    //Stores the token and the actual datetime in the user table
                    if ($userRepository->updateToken($user->id, $token)){
                        //Send the email with the password renewal link
                        $email = new Email("Blog Devcm", 
                        "", 
                        "contact@blog.devcm.fr", 
                        $_POST['emailAddress'],
                        "Renouvellement de mot de passe", 
                        "Bonjour, \r\n" . 
                        "Vous avez effectué une demande de renouvellement de mot de passe sur notre site blog.devcm.fr. \r\n" . 
                        "Pour cela veuillez cliquer sur le lien ci-dessous, ce dernier est valable 24 heures. \r\n" . 
                        "http://localhost/index.php?action=passwordRenewalSubscription&email=" . $_POST['emailAddress'] . 
                        "&token=" . $token . "\r\n" . 
                        "Cordialement. \r\n" . 
                        "Le service support"
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

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        
        echo $twig->render('passwordRenewal.twig', [ 
            'warningLogin' => $warningEmail, 
            'emailValue' => $emailValue,
            'errorMessage' =>$errorMessage,
            'successMessage' => $successMessage
        ]);
    }
}