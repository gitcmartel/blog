//Home page Form fields validation

//Html elements
var containerContactForm = document.getElementById("containerContactForm");
var contactForm = document.getElementById("contactForm");
var formSurname = document.getElementById("surname");
var formName = document.getElementById("name");
var formEmail = document.getElementById("email");
var formMessage = document.getElementById("message");

//Warning html elements
var alertSurname = document.getElementById("alertSurname");
var alertName = document.getElementById("alertName");
var alertEmail = document.getElementById("alertEmail");
var alertMessage = document.getElementById("alertMessage");

var emailRegExp = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

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


//When the submit button is pressed, if the fields values pass the tests
//then the form is submited, otherwise the warnings are displayed

contactForm.addEventListener("submit", function (event) {
    var validation = true;
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
