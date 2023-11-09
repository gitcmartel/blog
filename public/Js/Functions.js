
/**
 * 
 * Show or hide the content of a password field
 */
function showPassword(passwordElement, iconElement){
    if (passwordElement.type === "password") {
        passwordElement.type = "text";
        iconElement.classList.remove('fa-eye');
        iconElement.classList.add('fa-eye-slash');
      } else {
        passwordElement.type = "password";
        iconElement.classList.add('fa-eye');
        iconElement.classList.remove('fa-eye-slash');
      }
}

/**
 * Set the hidden html input element to 1
 * Then when a sumbission is performed, the php controller can know if it's a
 * validation or not
 */

function devalidation()
{
    let inputValidation = document.getElementById("validation");
    if(inputValidation !== null){
        inputValidation.setAttribute('value', '0');
    }
}


/**
 * Set the html elements to trigger the proper Controller
 */

function confirmationAction(elementType, id, title)
{
    let confirmationTitle = document.getElementById("confirmationModalLabel");
    let btnConfirmation = document.getElementById("btnConfirmationModal");
    let deletionMessage = document.getElementById("confirmationMessage");

    switch (elementType) {
        case "adminPost" :
            confirmationTitle.innerHTML = "Suppression"
            btnConfirmation.href = "index.php?action=Admin\\Post\\AdminPostDeletion&postId=" + id;
            deletionMessage.innerHTML = "Confirmez-vous la suppression du post " + title + " ?";
        break;

        case "adminUser" :
            confirmationTitle.innerHTML = "Suppression"
            btnConfirmation.href = "index.php?action=Admin\\User\\AdminUserDeletion&userId=" + id;
            deletionMessage.innerHTML = "Confirmez-vous la suppression de l'utilisateur " + title + " ?";
        break;

        case "adminComment" :
            confirmationTitle.innerHTML = "Suppression"
            btnConfirmation.href = "index.php?action=Admin\\Comment\\AdminCommentDeletion&commentId=" + id;
            deletionMessage.innerHTML = "Confirmez-vous la suppression du commentaire de " + title + " ?";
        break;

        case "comment" :
            confirmationTitle.innerHTML = "Suppression"
            btnConfirmation.href = "index.php?action=Comment\\CommentDeletion&commentId=" + id;
            deletionMessage.innerHTML = "Confirmez-vous la suppression du commentaire de " + title + " ?";
        break;


        case "disconnection" :
            confirmationTitle.innerHTML = "Déconnexion"
            btnConfirmation.href = "index.php?action=Connexion\\Disconnection";
            deletionMessage.innerHTML = "Confirmez-vous la déconnexion ?";
    }
}

/**
 * Change the action attribute of the form
 * to re-route the action to the AdminPostPublish Controller
 */

function publish(event)
{
    let formPost = document.getElementById("formPost");

    if(formPost !== null){
        formPost.action = "index.php?action=Admin\\Post\\AdminPostPublish"
    } else {
        event.preventDefault(); //Cancel form submission
    }
}
