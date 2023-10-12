<?php 

namespace Application\Controllers\Home;

use Application\Lib\FormValidation;
use Application\Lib\Email;
use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;
use Application\Lib\TwigLoader;

class Home 
{
    public function execute() {

        $messageResponse = $this->sendMessage();

        $twig = TwigLoader::getEnvironment();

        echo $twig->render('Home\Home.html.twig', [ 
            'messageResponse' => $messageResponse, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
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