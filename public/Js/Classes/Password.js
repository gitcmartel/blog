class Password 
{
    static showPassword(passwordElement, iconElement) {
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
}

