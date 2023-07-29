<?php 


namespace Application\Controllers;

use Application\Models\User;
use Application\Models\UserRepository;
use Application\Lib\Password;
use DateTime;

class Subscription 
{
    public function execute()
    {
        $warningEmail = "";
        $warningPseudo = "";
        $warningGeneral = "";

        //Checks if a new account creation has been sent
        if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['pseudo']) && isset($_POST['email']) 
        && isset($_POST['password']) && isset($_POST['passwordConfirmation'])) {
            if ($_POST['name'] !== "" && $_POST['surname'] !== "" && $_POST['pseudo'] !== "" && $_POST['email'] !== ""
            && $_POST['password'] !== "" && $_POST['passwordConfirmation'] !== "") {

                $userRepository = new UserRepository();


                $loader = new \Twig\Loader\FilesystemLoader('templates');
                $twig = new \Twig\Environment($loader);
                
                if ($userRepository->exists($_POST['email'], 'email')) {
                    $warningEmail = "Cette adresse email existe déjà";
                }

                if ($userRepository->exists($_POST['pseudo'], 'pseudo')) {
                    $warningPseudo = "Cette adresse email existe déjà";
                }

                //If the email and pseudo are available we create the user account
                if ($warningEmail === "" && $warningPseudo === ""){
                    if($userRepository->createUser(new User($_POST['name'], $_POST['surname'], $_POST['pseudo'], $_POST['email'], 
                    $_POST['password'], DateTime::createFromFormat("Y-m-d H:i:s", Date("Y-m-d H:i:s")), "Lecteur", 0))){
                        echo $twig->render('subscriptionSuccess.twig', []);  
                        return;
                    } else {
                        echo $twig->render('subscription.twig', [
                            'warningEmail' => $warningEmail, 
                            'warningPseudo' => $warningPseudo, 
                            'warningGeneral' => $warningGeneral
                        ]);  
                        return;
                    }
                } else {
                    echo $twig->render('subscription.twig', [
                        'warningEmail' => $warningEmail, 
                        'warningPseudo' => $warningPseudo, 
                        'warningGeneral' => $warningGeneral
                    ]);
                    return;
                }
            }
        }
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);

        echo $twig->render('subscription.twig', []);  
    }
}