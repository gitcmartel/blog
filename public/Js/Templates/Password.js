togglePasswordElement = document.getElementById("togglePassword");
togglePasswordConfirmationElement = document.getElementById("togglePasswordConfirmation");

togglePasswordElement.addEventListener("click", function() { 
    Password.showPassword(document.getElementById("password"), togglePasswordElement);
});

togglePasswordConfirmationElement.addEventListener("click", function() { 
    Password.showPassword(document.getElementById("passwordConfirmation"), togglePasswordConfirmationElement);
});