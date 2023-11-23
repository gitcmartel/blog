<?php 

namespace Application\Controllers\Home;

use Application\Lib\FormValidation;
use Application\Lib\Email;
use Application\Models\PostRepository;
use Application\Lib\Session;
use Application\Lib\TwigLoader;

class Home 
{
    #region Functions
    public function execute() {

        #region Variables
        $postRepository = new PostRepository();
        $twig = TwigLoader::getEnvironment();
        $messageResponse = "";

        #endregion

        #region Conditions tests

        //Get the latest 3 posts
        $posts = $postRepository->getPosts(1, 3, true);

        //If the fields are not set we simply display the home page
        if (! isset($_POST['surname']) && ! isset($_POST['name']) && ! isset($_POST['email']) && ! isset($_POST['message'])){
            echo $twig->render('Home\Home.html.twig', [ 
                'messageResponse' => "", 
                'posts' => $posts, 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => Session::getActiveUserFunction()
            ]);
            return;
        }

        //All the form fields have to be filled
        if (trim($_POST['surname']) === "" || trim($_POST['name']) === "" || trim($_POST['email']) === "" 
        || trim($_POST['message']) === "" ){
            echo $twig->render('Home\Home.html.twig', [ 
                'messageResponse' => "Vous devez compléter tous les champs du formulaire.", 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => Session::getActiveUserFunction()
            ]);
            return;
        }

        //Checks the validity of the form fields data
        $formValidation = new FormValidation(
            $_POST['surname'], 
            $_POST['name'], 
            $_POST['email'], 
            $_POST['message']
        );

        if ($formValidation->isValid()) {
            echo $twig->render('Home\Home.html.twig', [ 
                'messageResponse' => "Un ou plusieurs champs du formulaire sont invalides !", 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => Session::getActiveUserFunction()
            ]);
            return;
        }

        #endregion

        #region Function execution

        $mail = new Email(
            $_POST['surname'], 
            $_POST['name'],
            "contact@blog.devcm.fr",
            "contact@blog.devcm.fr",  
            "Nouveau contact !", 
            "Message envoyé depuis le formulaire de contact du Blog Devcm. \r\n" . 
            "Auteur : " . $_POST['surname'] . " " . $_POST['name'] . " - " . $_POST['email'] . "\r\n" . 
            $_POST['message']
        );

        if($mail->sendMail()) {
            $messageResponse = 'Le message a été envoyé.';
        } else {
            $messageResponse = 'Erreur de Mailer : ' . $mail->errorInfo;
        }

        echo $twig->render('Home\Home.html.twig', [ 
            'messageResponse' => $messageResponse, 
            'posts' => $posts, 
            'activeUser' => Session::getActiveUser(), 
            'userFunction' => Session::getActiveUserFunction()
        ]);

        #endregion
    }
    #endregion
}