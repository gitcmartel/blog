class Password 
{
    static showPassword(passwordElement, iconElement) {
        if (passwordElement.type === "password") {
            passwordElement.type = "text";
            iconElement.classList.remove('fa-eye-slash');
            iconElement.classList.add('fa-eye');
          } else {
            passwordElement.type = "password";
            iconElement.classList.add('fa-eye-slash');
            iconElement.classList.remove('fa-eye');
          }
    }
}

