<?php

namespace Application\Controllers\Admin\User;

use Application\Models\UserRepository;
use Application\Models\User;
use Application\Models\UserFunction;
use Application\Lib\UserActiveCheckValidity;
use Application\Lib\DatabaseConnexion;
use Application\Lib\Session;
use Application\Lib\Password;
use Application\Lib\TwigLoader;
use Application\Lib\TwigWarning;
use Application\Lib\Email;

class AdminUserSave
{
    #region Functions
    public function execute()
    {
        #region Variables

        $userRepository = new UserRepository();
        $twig = TwigLoader::getEnvironment();
        $passwordConfirmation = '';

        #endregion

        #region Conditions tests

        if(! UserActiveCheckValidity::check(array('Administrateur'))){
                TwigWarning::display(
                    "Vous n'avez pas les droits requis pour accéder à cette page. Contactez l'administrateur du site", 
                    "index.php?action=Home\Home", 
                    "Nous contacter");
                return; 
        }

        if (! isset($_POST['userId']) || ! isset($_POST['userName']) || ! isset($_POST['surname']) || ! isset($_POST['pseudo']) || ! isset($_POST['userPwd'])
        || ! isset($_POST['userPwdConfirmation']) || ! isset($_POST['userFunction']) || ! isset($_POST['userValidity'])){
            TwigWarning::display(
                "Une erreur est survenue lors du chargement de la page.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return;
        }

        $user = new User();
        
        $user->hydrate(array(
            'id' => trim($_POST["userId"]) === '' ? null : intval(trim($_POST["userId"])),
            'name' => trim($_POST['userName']), 
            'surname' => trim($_POST['surname']), 
            'pseudo' => trim($_POST['pseudo']), 
            'email' => trim($_POST['userMail']),
            'userFunction' => trim($_POST['userFunction']), 
            'isValid' => trim($_POST['userValidity']),
            'password' => trim($_POST['userPwd']),
        ));

        $passwordConfirmation = trim($_POST['userPwdConfirmation']);

        $fieldsWarnings = array(
            'name' => 'Vous devez renseigner un prénom', 
            'surname' => 'Vous devez renseigner un nom', 
            'pseudo' => 'Vous devez renseigner un pseudo', 
            'email' => Email::checkMailFormat($user->getEmail()) ? '' : 'L\'adresse email est incorrecte',  
            'userFunction' => 'Vous devez renseigner une fonction', 
            'password' => isset($_POST['passwordChange']) ? Password::checkPasswordFormFields($user->getPassword(), $passwordConfirmation) : '', 
            'validity' => 'Vous devez selectionner une option'
        );

        //If there is an incorrect field we display the error message
        if($user->getName() === "" || $user->getSurname() === "" || $user->getPseudo() === "" || $user->getEmail() === "" || 
        $user->getUserFunction() === "" || $user->getIsValid() === "" || $fieldsWarnings['email'] !== '' || $fieldsWarnings['password'] !== ''){
            echo $twig->render('Admin\User\AdminUser.html.twig', [ 
                'warningName' => $user->getName() === "" ? $fieldsWarnings['name'] : '', 
                'warningSurname' => $user->getSurname() === "" ? $fieldsWarnings['surname'] : '', 
                'warningPseudo' => $user->getPseudo() === "" ? $fieldsWarnings['pseudo'] : '', 
                'warningEmail' => $fieldsWarnings['email'], 
                'warningPassword' => $fieldsWarnings['password'], 
                'warningFunction' => $user->getUserFunction() === "" ? $fieldsWarnings['userFunction'] : '', 
                'warningValidity' => $user->getIsValid() === "" ? $fieldsWarnings['validity'] : '',  
                'user' => $user,
                'pwdChangeCheckedValue' => isset($_POST['passwordChange']) ? 'checked' : '',
                'userFunction' => Session::getActiveUserFunction(),
                'activeUser' => Session::getActiveUser()
            ]);
            return;
        }

        //Check if the userId variable is present in the database
        $userDatabase = $userRepository->getUser($user->getid());

        if($user->getId() !== null && ($userDatabase->getId() !== (int)$user->getId())){
            TwigWarning::display(
                "Un problème est survenu lors de l'enregistrement de l'utilisateur.", 
                "index.php?action=Home\Home", 
                "Retour à la page d'accueil");
            return; 
        }

        #endregion

        #region Function execution
        
        if ($user->getId() !== null){
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
        if ($userRepository->createUser($user)){
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