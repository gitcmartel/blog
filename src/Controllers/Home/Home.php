<?php

namespace Application\Controllers\Home;

use Application\Lib\FormValidation;
use Application\Lib\Email;
use Application\Models\PostRepository;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\Alert;

class Home
{
    //region Functions
    /**
     * Controller main function
     */
    public function execute(): void
    {

        //region Variables
        $postRepository = new PostRepository();
        $twig = TwigLoader::getEnvironment();
        $messageResponse = "";

        //endregion

        //region Conditions tests

        //Get the latest 3 posts
        $posts = $postRepository->getPosts(1, 3, true);

        $alert = filter_input(INPUT_GET, 'alert', FILTER_SANITIZE_SPECIAL_CHARS);
        $alertType = filter_input(INPUT_GET, 'alertType', FILTER_SANITIZE_SPECIAL_CHARS);

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

        //If the fields are not set we simply display the home page
        if (
            $name === false || $name === null || $surname === false || $surname === null ||
            $email === false || $email === null || $message === false || $message === null
        ) {
            echo $twig->render(
                'Home\Home.html.twig', [
                    'messageResponse' => "",
                    'posts' => $posts,
                    'alert' => ($alert !== false && $alert !== null) ? $alert : '',
                    'alertMessage' => ($alertType !== false && $alertType !== null) ? Alert::getMessage($alertType) : '',
                    'activeUser' => Session::getActiveUser(),
                    'userFunction' => Session::getActiveUserFunction()
                ]);
            return;
        }

        //All the form fields have to be filled
        if (trim($surname) === "" || trim($name) === "" || trim($email) === "" || trim($message) === "") {
            echo $twig->render('Home\Home.html.twig', [
                'warningMessage' => "Vous devez compléter tous les champs du formulaire !",
                'surname' => $surname,
                'name' => $name,
                'email' => $email,
                'message' => $message,
                'activeUser' => Session::getActiveUser(),
                'userFunction' => Session::getActiveUserFunction()
            ]);
            return;
        }

        //Checks the validity of the form fields data
        $formValidation = new FormValidation(
            $surname,
            $name,
            $email,
            $message
        );

        if (!$formValidation->isValid()) {
            echo $twig->render('Home\Home.html.twig', [
                'warningMessage' => "Un ou plusieurs champs du formulaire sont invalides !",
                'surname'        => $surname,
                'name'           => $name,
                'email'          => $email,
                'message'        => $message,
                'activeUser'     => Session::getActiveUser(),
                'userFunction'   => Session::getActiveUserFunction()
            ]);
            return;
        }

        //endregion

        //region Function execution

        $mail = new Email(
            $surname,
            $name,
            "contact@blog.devcm.fr",
            "contact@blog.devcm.fr",
            "Nouveau contact !",
            "Message envoyé depuis le formulaire de contact du Blog Devcm. \r\n" .
            "Auteur : " . $surname . " " . $name . " - " . $email . "\r\n" .
            $message
        );

        if ($mail->sendMail()) {
            $messageResponse = 'Le message a été envoyé.';
        } else {
            $messageResponse = 'Erreur de Mailer : ' . $mail->getErrorInfo();
        }

        echo $twig->render('Home\Home.html.twig', [
            'messageResponse' => $messageResponse,
            'alert' => true,
            'alertMessage' => $messageResponse,
            'posts' => $posts,
            'activeUser' => Session::getActiveUser(),
            'userFunction' => Session::getActiveUserFunction()
        ]);

        //endregion
    }
    //endregion
}
//end execute()

