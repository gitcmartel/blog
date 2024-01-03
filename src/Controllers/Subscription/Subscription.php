<?php


namespace Application\Controllers\Subscription;

use Application\Models\User;
use Application\Models\UserRepository;
use Application\Lib\Password;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;
use Application\Lib\Email;
use Application\Lib\Pseudo;

class Subscription
{
    //region Functions
    /**
     * Controller main function
     */
    public function execute(): void
    {
        //region Variables

        $twig = TwigLoader::getEnvironment();
        $userRepository = new UserRepository();

        //endregion 

        //region Conditions tests

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_SPECIAL_CHARS);
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        $passwordConfirmation = filter_input(INPUT_POST, 'passwordConfirmation', FILTER_DEFAULT);

        //Checks if a new account creation has been sent
        if (
            $name === false || $name === null || $surname === false || $surname === null ||
            $pseudo === false || $pseudo === null || $email === false || $email === null ||
            $password === false || $password === null || $passwordConfirmation === false || $passwordConfirmation === null
        ) {
            echo $twig->render('Subscription\Subscription.html.twig', [
                'warningName'     => '',
                'warningSurname'  => '',
                'warningEmail'    => '',
                'warningPseudo'   => '',
                'warningPassword' => '',
                'activeUser'      => Session::getActiveUser(),
                'userFunction'    => Session::getActiveUserFunction()
            ]);
            return;
        }

        $user = new User();

        $user->hydrate([
            "name"         => $name,
            "surname"      => $surname,
            "pseudo"       => $pseudo,
            "email"        => $email,
            "password"     => $password,
            "creationDate" => Date("Y-m-d H:i:s"),
            "userFunction" => 'Lecteur',
            'isValid'      => 1,
        ]);

        $fieldsWarnings = [
            'warningName'     => !$userRepository->checkNameSurname($name) ? 'Le champ prénom doit être complété (50 caractères max)' : '',
            'warningSurname'  => !$userRepository->checkNameSurname($surname) ? 'Le champ prénom doit être complété (50 caractères max)' : '',
            'warningEmail'    => Email::checkMailFormat($email) ? '' : 'L\'adresse email est incorrecte',
            'warningPseudo'   => Pseudo::checkPseudo($pseudo),
            'warningPassword' => Password::checkPasswordFormFields($password, $passwordConfirmation)
        ];

        //If there is a warning to display (incorrect field content)
        if (
            !($fieldsWarnings["warningName"] === "" && $fieldsWarnings["warningSurname"] === "" && $fieldsWarnings["warningEmail"] === ""
                && $fieldsWarnings["warningPseudo"] === "" && $fieldsWarnings["warningPassword"] === "")
        ) {
            echo $twig->render('Subscription\Subscription.html.twig', [
                'warningName'     => $fieldsWarnings["warningName"],
                'warningSurname'  => $fieldsWarnings["warningSurname"],
                'warningEmail'    => $fieldsWarnings["warningEmail"],
                'warningPseudo'   => $fieldsWarnings["warningPseudo"],
                'warningPassword' => $fieldsWarnings["warningPassword"],
                'user'            => $user,
                'activeUser'      => Session::getActiveUser(),
                'userFunction'    => Session::getActiveUserFunction()
            ]);
            return;
        }

        //endregion

        //region Function execution

        if ($userRepository->createUser($user)) {
            header("Location:index.php?action=Home\Home&alert=true&alertType=SubscriptionSuccess");
        } else {
            TwigWarning::display(
                "Un problème est survenu lors de la création du compte.",
                "index.php?action=Subscription\Subscription",
                "Réessayer");
        }

        //endregion
    }
    //endregion
}
//End execute()

