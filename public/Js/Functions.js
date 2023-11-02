//Home page Form fields validation

//Html elements
let containerContactForm = document.getElementById("containerContactForm");
let contactForm = document.getElementById("contactForm");
let subscriptionForm = document.getElementById("subscriptionForm");
let formSurname = document.getElementById("surname");
let formName = document.getElementById("name");
let formEmail = document.getElementById("email");
let formMessage = document.getElementById("message");

//Warning html elements
let alertSurname = document.getElementById("alertSurname");
let alertName = document.getElementById("alertName");
let alertEmail = document.getElementById("alertEmail");
let alertMessage = document.getElementById("alertMessage");

let emailRegExp = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

//Validation functions
function validationNameSurnameMesssage(element, elementMinLength, elementMaxLength)
{
    if(element === null || element.value === ""){
        return false;
    } else if(element.length > elementMaxLength) {
        return false
    } else if(element.length <= elementMinLength) {
        return false
    } else {
        return true;
    }
}

function validationEmail(element)
{
    if(element === null || element.value === ""){
        return false;
    } else if (!emailRegExp.test(element.value)) {
        return false
    } else {
        return true;
    }
}

function validationPassword(password){
    let regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[`!@#$%^&*()_+\-=\[\]{};':\\|,.<>\/?~])(?=.{8,})/;
    let test= regex.test(password.value);
    return test;
}

function validationEqualityPassword(password1, password2){
    return (password1.value === password2.value);
}


//When the submit button is pressed, if the fields values pass the tests
//then the form is submited, otherwise the warnings are displayed
if (contactForm !== null) {
    contactForm.addEventListener("submit", function (event) {
        let validation = true;
        if(validationNameSurnameMesssage(formSurname, 0, 50) === false) {
            alertSurname.style.display = "block";
            validation = false;
        } else {
            alertSurname.style.display = "none";
        }
    
        if(validationNameSurnameMesssage(formName, 0, 50) === false) {
            alertName.style.display = "block";
            validation = false;
        } else {
            alertName.style.display = "none";
        }
    
        if(validationNameSurnameMesssage(formMessage, 50, 50) === false) {
            alertMessage.style.display = "block";
            validation = false;
        } else {
            alertMessage.style.display = "none";
        }
    
        if(validationEmail(formEmail) === false) {
            alertEmail.style.display = "block";
            validation = false;
            alertEmail.innerHTML = "Cette adresse email est incorrecte";
        } else {
            alertEmail.style.display = "none";
        }
    
        if (!validation){
            containerContactForm.style.height = "65vh";
            event.preventDefault();
        }
    });
}

function showPassword(element){
    var passwordField = document.getElementById(element);
    if (passwordField.type === "password") {
        passwordField.type = "text";
        element.classList.remove('fa-eye');
        element.classList.add('fa-eye-slash');
      } else {
        passwordField.type = "password";
        element.classList.add('fa-eye');
        element.classList.remove('fa-eye-slash');
      }
}

/**
 * Set the hidden html input element to 1
 * Then when a sumbission is performed, the php controller can know if it's a
 * publication or not
 */
function unPublish()
{
    let inputUnpublish = document.getElementById("unpublish");
    if(inputUnpublish !== null){
        inputUnpublish.setAttribute('value', 'true');
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
 * Changes the form action to trigger a post search 
 */

function searchPosts(event)
{
    let formPostList = document.getElementById("formPostList");
    let searchString = document.getElementById("searchString");

    if(formPostList !== null && searchString !== null){
        if(searchString.value.trim() !== ""){
            formPostList.action = "index.php?action=Admin\\Post\\AdminPostSearch"
        } else {
            event.preventDefault(); //Cancel form submission
        }
    } else {
        event.preventDefault(); //Cancel form submission
    }
}

/**
 * Changes the form action to trigger a user search 
 */

function searchUsers(event)
{
    let formUserList = document.getElementById("formUserList");
    let searchString = document.getElementById("searchString");

    if(formUserList !== null && searchString !== null){
        if(searchString.value.trim() !== ""){
            formUserList.action = "index.php?action=Admin\\User\\AdminUserSearch"
        } else {
            event.preventDefault(); //Cancel form submission
        }
    } else {
        event.preventDefault(); //Cancel form submission
    }
}

/**
 * Changes the form action to trigger a comment search 
 */

function searchComments(event)
{
    let formCommentList = document.getElementById("formCommentList");
    let searchString = document.getElementById("searchString");

    if(formCommentList !== null && searchString !== null){
        if(searchString.value.trim() !== ""){
            formCommentList.action = "index.php?action=Admin\\Comment\\AdminCommentSearch"
        } else {
            event.preventDefault(); //Cancel form submission
        }
    } else {
        event.preventDefault(); //Cancel form submission
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

/**
 * Display the selected image post
 */

function displayImagePost(e)
{           
    var image = document.getElementById("imagePost");

        // e.files contient un objet FileList
        const [picture] = e.files

        // "picture" est un objet File
        if (picture) {
            // On change l'URL de l'image
            image.src = URL.createObjectURL(picture)
        }
}

/**
 * Reset the image of a post
 */

function resetImagePost()
{
    let image = document.getElementById("imagePost");
    let imagePath = document.getElementById("imagePath");
    let resetImage = document.getElementById("resetImage");

    image.src = "img/blog-post.svg";
    imagePath.defaultValue = "img/blog-post.svg";
    resetImage.defaultValue = "true";
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
        case "post" :
            confirmationTitle.innerHTML = "Suppression"
            btnConfirmation.href = "index.php?action=Admin\\Post\\AdminPostDeletion&postId=" + id;
            deletionMessage.innerHTML = "Confirmez-vous la suppression du post " + title + " ?";
        break;

        case "user" :
            confirmationTitle.innerHTML = "Suppression"
            btnConfirmation.href = "index.php?action=Admin\\User\\AdminUserDeletion&userId=" + id;
            deletionMessage.innerHTML = "Confirmez-vous la suppression de l'utilisateur " + title + " ?";
        break;

        case "comment" :
            confirmationTitle.innerHTML = "Suppression"
            btnConfirmation.href = "index.php?action=Admin\\Comment\\AdminCommentDeletion&commentId=" + id;
            deletionMessage.innerHTML = "Confirmez-vous la suppression du commentaire de " + title + " ?";
        break;

        case "disconnection" :
            confirmationTitle.innerHTML = "Déconnexion"
            btnConfirmation.href = "index.php?action=Connexion\\Disconnection";
            deletionMessage.innerHTML = "Confirmez-vous la déconnexion ?";
    }
}

