<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\Session;
use Application\Lib\Password;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;
use Application\Lib\Email;

class AdminUserSave
{
    #region Functions
    /**
     * Controller function execute
     */
    public function execute(): void
    {
        #region Variables

        $userRepository = new UserRepository();
        $twig = TwigLoader::getEnvironment();
        $passwordConfirmation = '';

        #endregion

        #region Conditions tests

        if (UserActiveCheckValidity::check(array('Administrateur')) === false) {
            TwigWarning::display(
                "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site",
                "index.php?action=Home\Home",
                "Nous contacter");
            return;
        }

        $userId = filter_input(INPUT_POST, 'userId', FILTER_SANITIZE_NUMBER_INT);
        $userName = filter_input(INPUT_POST, 'userName', FILTER_SANITIZE_SPECIAL_CHARS);
        $userSurname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_SPECIAL_CHARS);
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_SPECIAL_CHARS);
        $userMail = filter_input(INPUT_POST, 'userMail', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
        $passwordConfirmation = filter_input(INPUT_POST, 'passwordConfirmation', FILTER_UNSAFE_RAW);
        $userFunction = filter_input(INPUT_POST, 'userFunction', FILTER_SANITIZE_SPECIAL_CHARS);
        $userValidity = filter_input(INPUT_POST, 'userValidity', FILTER_VALIDATE_BOOLEAN);
        $passwordChange = filter_input(INPUT_POST, 'passwordChange', FILTER_VALIDATE_BOOLEAN);

        if (
            $userId === false || $userId === null || $userName === false || $userName === null || $userSurname === false || $userSurname === null ||
            $pseudo === false || $pseudo === null || $userMail === false || $userMail === null || $password === false || $password === null ||
            $passwordConfirmation === false || $passwordConfirmation === null ||
            $userFunction === false || $userFunction === null || $userValidity === null
        ) {
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.",
                "index.php?action=Home\Home",
                "Retour à la page d'accueil");
            return;
        }

        $user = new User();

        $user->hydrate([
            'id'           => (int) $userId,
            'name'         => trim($userName),
            'surname'      => trim($userSurname),
            'pseudo'       => trim($pseudo),
            'email'        => trim($userMail),
            'userFunction' => trim($userFunction),
            'isValid'      => trim($userValidity),
            'password'     => trim($password),
        ]);

        $passwordConfirmation = trim($passwordConfirmation);

        $fieldsWarnings = [
            'name' => 'Vous devez renseigner un prénom',
            'surname' => 'Vous devez renseigner un nom',
            'pseudo' => 'Vous devez renseigner un pseudo',
            'email' => Email::checkMailFormat($user->getEmail()) === true ? '' : 'L\'adresse email est incorrecte',
            'userFunction' => 'Vous devez renseigner une fonction',
            'password' => $passwordChange === true ? Password::checkPasswordFormFields($user->getPassword(), $passwordConfirmation) : '',
            'validity' => 'Vous devez selectionner une option'
        ];

        //If there is an incorrect field we display the error message
        if (
            $user->getName() === "" || $user->getSurname() === "" || $user->getPseudo() === "" || $user->getEmail() === "" ||
            $user->getUserFunction() === "" || $user->getIsValid() === "" || $fieldsWarnings['email'] !== '' || $fieldsWarnings['password'] !== ''
        ) {
            echo $twig->render('Admin\User\AdminUser.html.twig', [
                'warningName' => $user->getName() === "" ? $fieldsWarnings['name'] : '',
                'warningSurname' => $user->getSurname() === "" ? $fieldsWarnings['surname'] : '',
                'warningPseudo' => $user->getPseudo() === "" ? $fieldsWarnings['pseudo'] : '',
                'warningEmail' => $fieldsWarnings['email'],
                'warningPassword' => $fieldsWarnings['password'],
                'warningFunction' => $user->getUserFunction() === "" ? $fieldsWarnings['userFunction'] : '',
                'warningValidity' => $user->getIsValid() === "" ? $fieldsWarnings['validity'] : '',
                'user' => $user,
                'pwdChangeCheckedValue' => $passwordChange === true ? 'checked' : '',
                'userFunction' => Session::getActiveUserFunction(),
                'activeUser' => Session::getActiveUser()
            ]);
            return;
        }

        #endregion

        #region Function execution

        if ($user->getId() !== 0) {
            //If there is a userId we update
            if ($userRepository->updateUser($user)) {
                //We display the updated user list
                header("Location:index.php?action=Admin\User\AdminUserList&pageNumber=1");
                return;
            } else {
                TwigWarning::display(
                    "Un problème est survenu lors de l'enregistrement de l'utilisateur.",
                    "index.php?action=Home\Home",
                    "Retour à l'accueil");
                return;
            }
        }

        //If there is no userId we create a new user
        if ($userRepository->createUser($user)) {
            //We display the updated user list
            header("Location:index.php?action=Admin\User\AdminUserList&pageNumber=1");
            return;
        } else {
            TwigWarning::display(
                "Un problème est survenu lors de l'enregistrement de l'utilisateur.",
                "index.php?action=Home\Home",
                "Retour à l'accueil");
            return;
        }

        #endregion
    }
    #endregion
}
//end execute()

