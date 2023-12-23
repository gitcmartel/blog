<?php


namespace Application\Controllers\UserAccount;

use Application\Models\UserRepository;
use Application\Lib\Password;
use Application\Lib\Session;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;
use Application\Lib\Email;
use Application\Lib\Pseudo;

class UserAccount
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
        $passwordChange = filter_input(INPUT_POST, 'passwordChange', FILTER_VALIDATE_BOOLEAN);

        //Checks if an account update has been sent
        if (
            $name === false || $name === null || $surname === false || $surname === null ||
            $pseudo === false || $pseudo === null || $email === false || $email === null ||
            $password === false || $password === null || $passwordConfirmation === false || $passwordConfirmation === null
        ) {

            $user = $userRepository->getUser(Session::getActiveUserId());

            echo $twig->render(
                'UserAccount\UserAccount.html.twig', [
                'user'         => $user,
                'activeUser'   => Session::getActiveUser(),
                'userFunction' => Session::getActiveUserFunction()
            ]);
            return;
        }

        $user = $userRepository->getUser(Session::getActiveUserId());

        $fieldsWarnings = [
            'warningName'     => $userRepository->checkNameSurname($name) ? 'Le champ prénom doit être complété (50 caractères max)' : '',
            'warningSurname'  => !$userRepository->checkNameSurname($surname) ? 'Le champ nom doit être complété (50 caractères max)' : '',
            'warningEmail'    => Email::checkMailFormat($email) ? '' : 'L\'adresse email est incorrecte',
            'warningPseudo'   => $user->getPseudo() !== trim($pseudo) ? Pseudo::checkPseudo(trim($pseudo)) : '',
            'warningPassword' => $passwordChange === true ? Password::checkPasswordFormFields(trim($password), $passwordConfirmation) : ''
        ];

        $user->hydrate([
            "name"     => trim($name),
            "surname"  => trim($surname),
            "pseudo"   => trim($pseudo),
            "email"    => trim($email),
            "password" => $passwordChange === true ? $password : ""
        ]);

        //If there is a warning to display (incorrect field content)
        if (
            ($fieldsWarnings["warningName"] === "" && $fieldsWarnings["warningSurname"] === "" && $fieldsWarnings["warningEmail"] === ""
                && $fieldsWarnings["warningPseudo"] === "" && $fieldsWarnings["warningPassword"] === "") === false
        ) {
            echo $twig->render(
                'UserAccount\UserAccount.html.twig', [
                'warningName'           => $fieldsWarnings["warningName"],
                'warningSurname'        => $fieldsWarnings["warningSurname"],
                'warningEmail'          => $fieldsWarnings["warningEmail"],
                'warningPseudo'         => $fieldsWarnings["warningPseudo"],
                'warningPassword'       => $passwordChange === true ? Password::checkPasswordFormFields($user->getPassword(), $passwordConfirmation) : '',
                'pwdChangeCheckedValue' => $passwordChange === true ? 'checked' : '',
                'user'                  => $user,
                'activeUser'            => Session::getActiveUser(),
                'userFunction'          => Session::getActiveUserFunction()
            ]);
            return;
        }

        //endregion

        //region Function execution

        if ($userRepository->updateUser($user)) {
            header("Location:index.php?action=Home\Home");
        } else {
            TwigWarning::display(
                "Un problème est survenu lors de la mise à jour de votre compte.",
                "index.php?action=UserAccount\UserAccount",
                "Réessayer");
        }

        //endregion
    }
    //endregion
}
//End execute()
