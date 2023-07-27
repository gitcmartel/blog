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

function validatationPassword(password){
    let regex = new RegExp(/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/);
    return regex.test(mdp);
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
        } else {
            alertEmail.style.display = "none";
        }
    
        if (!validation){
            containerContactForm.style.height = "65vh";
            event.preventDefault();
        }
    });
}


//Check if the email address or pseudo already exists in the database
if (subscriptionForm !== null) {
    subscriptionForm.addEventListener("submit", function (event) {
        let email = document.getElementById("email");
        let pseudo = document.getElementById("pseudo");
        let warningEmail = document.getElementById("warningEmail");
        let warningPseudo = document.getElementById("warningPseudo");
        warningEmail.style.display = "none";
        warningPseudo.style.display = "none";
    
        if (email !== null && pseudo !== null && email.value !== "" && pseudo.value !== "") {
            let validation = true;
            let xhttp = new XMLHttpRequest;
            let url = "index.php?action=UserExists&email=" + email.value + "&pseudo=" + pseudo.value;
            xhttp.open("GET", url, false);
            xhttp.send();
            let response = xhttp.responseText;
            if (response !== ""){
                let jsonResponse = JSON.parse(response);
                if (jsonResponse.email === true){
                    warningEmail.style.display = "block";
                    validation = false;
                }
                if (jsonResponse.pseudo === true){
                    warningPseudo.style.display = "block";
                    validation = false;
                }
            }
    
            if (!validation){
                event.preventDefault();
            }
        }
    });
}

