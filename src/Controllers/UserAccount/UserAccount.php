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
    #region Functions
    public function execute()
    {
        #region Variables

        $twig = TwigLoader::getEnvironment();
        $userRepository = new UserRepository();
        
        #endregion 

        #region Conditions tests
        
        //Checks if an account update has been sent
        if (! (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['pseudo']) && isset($_POST['email']) 
        && isset($_POST['password']) && isset($_POST['passwordConfirmation']))) {

            $user = $userRepository->getUser(Session::getActiveUserId());

            echo $twig->render('UserAccount\UserAccount.html.twig', [
                'user' => $user, 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => Session::getActiveUserFunction()
            ]);  
            return; 
        }

        $user = $userRepository->getUser(Session::getActiveUserId());

        $passwordConfirmation = trim($_POST['passwordConfirmation']);
        
        $fieldsWarnings = array(
            'warningName' => ! $userRepository->checkNameSurname($_POST['name']) ? 'Le champ prénom doit être complété (50 caractères max)' : '',
            'warningSurname' => ! $userRepository->checkNameSurname($_POST['surname']) ? 'Le champ nom doit être complété (50 caractères max)' : '',
            'warningEmail' => Email::checkMailFormat($_POST['email']) ? '' : 'L\'adresse email est incorrecte',  
            'warningPseudo' => $user->getPseudo() !== trim($_POST['pseudo']) ? Pseudo::checkPseudo(trim($_POST['pseudo'])) : '', 
            'warningPassword' => isset($_POST['passwordChange']) ? Password::checkPasswordFormFields(trim($_POST['password']), $passwordConfirmation) : ''
        );

        $user->hydrate(array (
            "name"=> trim($_POST["name"]),
            "surname"=> trim($_POST["surname"]),
            "pseudo"=> trim($_POST["pseudo"]),
            "email"=> trim($_POST["email"]),
            "password"=> isset($_POST['passwordChange']) ? $_POST["password"] : ""
        ));

        //If there is a warning to display (incorrect field content)
        if (! ($fieldsWarnings["warningName"] === "" && $fieldsWarnings["warningSurname"] === "" && $fieldsWarnings["warningEmail"] === "" 
        && $fieldsWarnings["warningPseudo"] === "" && $fieldsWarnings["warningPassword"] === "")){
            echo $twig->render('UserAccount\UserAccount.html.twig', [
                'warningName' => $fieldsWarnings["warningName"], 
                'warningSurname' => $fieldsWarnings["warningSurname"], 
                'warningEmail' => $fieldsWarnings["warningEmail"], 
                'warningPseudo' => $fieldsWarnings["warningPseudo"], 
                'warningPassword' => isset($_POST['passwordChange']) ? Password::checkPasswordFormFields($user->getPassword(), $passwordConfirmation) : '',  
                'pwdChangeCheckedValue' => isset($_POST['passwordChange']) ? 'checked' : '',
                'user' => $user, 
                'activeUser' => Session::getActiveUser(), 
                'userFunction' => Session::getActiveUserFunction()
            ]);  
            return;
        }

        #endregion

        #region Function execution

        if($userRepository->updateUser($user)){
            header("Location:index.php?action=Home\Home");
        } else {
            TwigWarning::display(
                "Un problème est survenu lors de la mise à jour de votre compte.", 
                "index.php?action=UserAccount\UserAccount", 
                "Réessayer");
        }

        #endregion
    }
    #endregion
}