class Password 
{
    constructor() {
      this.togglePasswordElement = document.getElementById("togglePassword");
      this.togglePasswordConfirmationElement = document.getElementById("togglePasswordConfirmation");
      this.passwordField = document.getElementById("password");
      this.passwordConfirmationField = document.getElementById("passwordConfirmation");
      this.grpPasswordField = document.getElementById("grpUserPwd");
      this.grpPasswordConfirmationField = document.getElementById("grpUserPwdConfirmation");
      this.checkBoxPasswordField = document.getElementById("passwordChange");

      this.checkBoxPasswordField.addEventListener('click', this.displayPwdFields.bind(this));
      //password.addListener("click", "passwordChange", "displayPwdFields");
      this.togglePasswordElement.addEventListener('click', this.showPassword.bind(this));
      //password.addListener("click", "togglePassword", "showPassword");
      //password.addListener("click", "togglePasswordConfirmation", "showPassword");
      this.togglePasswordConfirmationElement.addEventListener('click', this.showPassword.bind(this));
    }

    showPassword() {
        if (this.passwordField.type === "password" && this.passwordConfirmationField.type === "password") {
            this.passwordField.type = "text";
            this.passwordConfirmationField.type = "text";
            this.togglePasswordElement.classList.remove('fa-eye-slash');
            this.togglePasswordElement.classList.add('fa-eye');
            this.togglePasswordConfirmationElement.classList.remove('fa-eye-slash');
            this.togglePasswordConfirmationElement.classList.add('fa-eye');
          } else {
            this.passwordField.type = "password";
            this.passwordConfirmationField.type = "password";
            this.togglePasswordElement.classList.add('fa-eye-slash');
            this.togglePasswordElement.classList.remove('fa-eye');
            this.togglePasswordConfirmationElement.classList.add('fa-eye-slash');
            this.togglePasswordConfirmationElement.classList.remove('fa-eye');
          }
    }

    displayPwdFields()
    {
      if(this.checkBoxPasswordField !== null){
        if (this.checkBoxPasswordField.checked) {
            this.grpPasswordField.classList.remove("d-none");
            this.grpPasswordConfirmationField.classList.remove("d-none");
        } else {
          this.grpPasswordField.classList.add("d-none");
          this.grpPasswordConfirmationField.classList.add("d-none");
        }
      }
    }
}

