<?php 

namespace Application\Controllers;

use Application\Lib\FormValidation;
use Application\Lib\Email;

class Home 
{
    public function execute() {
        $messageResponse = $this->sendMessage();

        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);
        
        echo $twig->render('home.twig', [ 
            'messageResponse' => $messageResponse
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
                        $_POST['email'], 
                        "Nouveau contact !", 
                        $_POST['message']
                    );

                    $response = $mail->sendMail();

                } else {
                    $response = "Un ou plusieurs champs du formulaire sont invalides !";
                }
            }
        }
        return $response;
    }
}