passwordInputElement = document.getElementById("password");
togglePasswordElement = document.getElementById("togglePassword");
passwordConfirmationInputElement = document.getElementById("passwordConfirmation");
togglePasswordConfirmationElement = document.getElementById("togglePasswordConfirmation");

togglePasswordElement.addEventListener("click", function() { 
    showPassword(passwordInputElement, togglePasswordElement);
});

togglePasswordConfirmationElement.addEventListener("click", function() { 
    showPassword(passwordConfirmationInputElement, togglePasswordConfirmationElement);
});