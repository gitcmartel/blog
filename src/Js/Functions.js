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


if (subscriptionForm !== null) {
    subscriptionForm.addEventListener("submit", function (event) {
        let name = document.getElementById("name");
        let surname = document.getElementById("surname");
        let email = document.getElementById("email");
        let pseudo = document.getElementById("pseudo");
        let password = document.getElementById("password");
        let passwordConfirmation = document.getElementById("passwordConfirmation");
        let warningEmail = document.getElementById("warningEmail");
        let warningPseudo = document.getElementById("warningPseudo");

        
        let validation = true;
        //Check the fields content
        if(validationNameSurnameMesssage(name, 0, 50) === false) {
            warningName.innerHTML = "Le champ prénom doit être complété (50 caractères max)";
            validation = false;
        } else {
            warningName.innerHTML = "";
        }

        if(validationNameSurnameMesssage(surname, 0, 50) === false) {
            warningSurname.innerHTML = "Le champ nom doit être complété (50 caractères max)";
            validation = false;
        } else {
            warningSurname.innerHTML = "";
        }

        if(validationNameSurnameMesssage(pseudo, 0, 50) === false) {
            warningPseudo.innerHTML = "Le champ pseudo doit être complété (50 caractères max)";
            validation = false;
        } else {
            warningPseudo.innerHTML = "";
        }

        if(validationEmail(email) === false) {
            warningEmail.innerHTML = "L'adresse email est incorrrecte"
            validation = false;
        } else {
            warningEmail.innerHTML = "";
        }

        if(validationPassword(password) === false) {
            warningPassword.innerHTML = "Le mot de passe doit être composé d'au moins 8 caractères, 1 majuscule, 1 minuscule, 1 nombre et 1 caractère spécial"
            validation = false;
        } else {
            warningPassword.innerHTML = "";
        }

        if(validationEqualityPassword(password, passwordConfirmation) === false) {
            warningPassword.innerHTML = "Les deux mots de passe ne correspondent pas"
            validation = false;
        } else {
            warningPassword.innerHTML = "";
        }

        //If the fields content are incorrect we don't go further
        if (!validation){
            event.preventDefault();
            return;
        }

        //Check if the email address or pseudo already exists in the database
        if (email !== null && pseudo !== null && email.value !== "" && pseudo.value !== "") {
            
            let xhttp = new XMLHttpRequest;
            let url = "index.php?action=UserExists&email=" + email.value + "&pseudo=" + pseudo.value;
            xhttp.open("GET", url, false);
            xhttp.send();
            let response = xhttp.responseText;

            if (response !== ""){
                let jsonResponse = JSON.parse(response);
                if (jsonResponse.email === true){
                    warningEmail.innerHTML = "Cette adresse email est déjà utilisée";
                    validation = false;
                }
                if (jsonResponse.pseudo === true){
                    warningPseudo.innerHTML = "Ce pseudo est déjà utilisé";
                    validation = false;
                }
            }
    
            if (!validation){
                event.preventDefault();
            }
        }
    });
}

/**
 * Set the hidden html input element to 1
 * Then when a sumbission is performed, the php controller can now if it's a
 * publication (0) or not (1)
 */
function unPublish()
{
    let inputUnpublish = document.getElementById("unpublish");
    if(inputUnpublish !== null){
        inputUnpublish.setAttribute('value', 'true');
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
        formPost.action = "index.php?action=AdminPostPublish"
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