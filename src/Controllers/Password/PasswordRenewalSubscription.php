<?php

namespace Application\Controllers\Password;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Password;
use Application\Lib\TwigLoader;
use DateTime;

class PasswordRenewalSubscription
{
    #region Functions
    public function execute()
    {
        $errorMessage = "";
        $passwordValue = "";
        $warningMessage = "";

        if(isset($_GET['token']) && isset($_GET['email'])){
            $userRepository = new UserRepository(new DatabaseConnexion);

            //If the user has already entered his new password
            if(isset($_POST['password']) && isset($_POST['passwordConfirmation'])){
                //Check the password validity
                if (! Password::checkPassword($_POST['password'])){
                    $warningMessage = "Le mot de passe doit être composé d'au moins 8 caractères, 1 majuscule, 1 minuscule, 1 nombre et 1 caractère spécial";
                }

                //Check if the passwords are the same
                if(trim($_POST['password']) !== trim($_POST['passwordConfirmation'])){
                    $warningMessage = "Les deux mots de passe ne correspondent pas";
                }

                $passwordValue = $_POST['password'];

                //If the passwords are ok we proceed
                if($warningMessage === ""){
                    //We check again if the token and it's date are valid
                    $user = $userRepository->isTokenValid($_GET['token'], $_GET['email']);
                    if($this->checkValidity($user)) {
                        //We update the new password and set the token field and it's date to null
                        if($userRepository->changePassword($user->id, $_POST['password'])){
                            $twig = TwigLoader::getEnvironment();
                            
                            echo $twig->render('Password\PasswordRenewalSuccess.html.twig', []);
                            return;
                        } else {
                            $errorMessage = "Une erreur est survenue lors de la tentative de renouvellement du mot de passe";
                        }
                    }
                } else {
                    $twig = TwigLoader::getEnvironment();
                    
                    echo $twig->render('Password\PasswordRenewalSubscription.html.twig', [
                        'warningMessage' => $warningMessage, 
                        'errorMessage' => $errorMessage
                    ]);
                    return;
                }
            } else {   
                //We check if the token and it's date are valid
                $user = $userRepository->isTokenValid($_GET['token'], $_GET['email']);
                if($this->checkValidity($user)) {
                    $loader = new \Twig\Loader\FilesystemLoader('templates');
                    $twig = new \Twig\Environment($loader);
                    
                    echo $twig->render('Password\PasswordRenewalSubscription.html.twig', [
                        'warningMessage' => $warningMessage, 
                        'errorMessage' => $errorMessage
                    ]);
                    return;
                } else {
                    $errorMessage = "Ce lien n'est plus valide, veuillez générer une nouvelle demande de renouvellement de mot de passe";
                }
            }
        } else {
            $errorMessage = "Ce lien n'est plus valide, veuillez générer une nouvelle demande de renouvellement de mot de passe";
        }

        $twig = TwigLoader::getEnvironment();
        
        echo $twig->render('Password\PasswordRenewalInvalid.html.twig', [ 
            'errorMessage' => $errorMessage,
        ]);
    }

    /**
     * Checks if the token and it's generation date are valid
     */
    private function checkValidity(User $user) : bool
    {
        //If we get a valid user then we proceed
        if(isset($user->id)){
            //Check if the date is no more than 24h old
            $dateDifference = $user->forgotPasswordDate->diff(new DateTime());
            if($dateDifference->s <= 86400){
               return true;
            }
        }
        return false;
    }
    #endregion
}