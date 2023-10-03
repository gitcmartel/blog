<?php 

namespace Application\Controllers;

use Application\Lib\FormValidation;
use Application\Lib\Email;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;

class Home 
{
    public function execute() {
        $userFunction = "";
        //Get the function of the active user
        if(isset($_SESSION['userId'])){
            $userRepository = new UserRepository(new DatabaseConnexion);
            $user = $userRepository->getUser($_SESSION['userId']);
            $userFunction = $user->userFunction;
        }

        $messageResponse = $this->sendMessage();

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, ['cache' => false]);
        
        echo $twig->render('home.twig', [ 
            'messageResponse' => $messageResponse, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => $userFunction
        ]);
    }


    //Send the message if the completed form fields are correct
    private function sendMessage() : string 
    {   
        $response = "";
        if (isset($_POST['surname']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])){
            if (trim($_POST['surname']) !== "" && trim($_POST['name']) !== "" && trim($_POST['email']) !== "" 
            && trim($_POST['message']) !== "" ){
                $formValidation = new FormValidation(
                    $_POST['surname'], 
                    $_POST['name'], 
                    $_POST['email'], 
                    $_POST['message']
                );
                
                if ($formValidation->isValid()) {
                    $mail = new Email(
                        $_POST['surname'], 
                        $_POST['name'],
                        "contact@blog.devcm.fr",
                        "contact@blog.devcm.fr",  
                        $_POST['email'], 
                        "Nouveau contact !", 
                        "Message envoyé depuis le formulaire de contact du Blog Devcm. \r\n" . 
                        "Auteur : " . $_POST['surname'] . " " . $_POST['name'] . " - " . $_POST['email'] . "\r\n" . 
                        $_POST['message']
                        
                    );

                    if($mail->sendMail()) {
                        $response = 'Le message a été envoyé.';
                    } else {
                        $response = 'Erreur de Mailer : ' . $mail->errorInfo;
                    }

                } else {
                    $response = "Un ou plusieurs champs du formulaire sont invalides !";
                }
            }
        }
        return $response;
    }
}